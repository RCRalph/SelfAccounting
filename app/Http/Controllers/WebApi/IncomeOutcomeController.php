<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Currency;
use App\Income;
use App\Outcome;
use App\MeanOfPayment;

use App\Rules\CorrectDateIO;
use App\Rules\ValidCategoryMean;
use App\Http\Middleware\IncomeOutcome;

class IncomeOutcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", IncomeOutcome::class]);
    }

    private function getData($viewType)
    {
        $currencies = Currency::all();

        $nullArray = [
            "id" => 0,
            "name" => "N / A"
        ];

        // Get categories
        $categories = auth()->user()->categories
            ->where($viewType . "_category", true)
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id"]))
            ->groupBy("currency_id")
            ->toArray();

        foreach ($currencies as $currency) {
            if (isset($categories[$currency["id"]])) {
                array_unshift($categories[$currency["id"]], $nullArray);
            }
            else {
                $categories[$currency["id"]] = [
                    0 => $nullArray
                ];
            }
        }

        // Get means
        $means = auth()->user()->meansOfPayment
            ->where($viewType . "_mean", true)
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id", "first_entry_date"]))
            ->groupBy("currency_id")
            ->toArray();

        $nullArray["first_entry_date"] = null;
        foreach ($currencies as $currency) {
            if (isset($means[$currency["id"]])) {
                array_unshift($means[$currency["id"]], $nullArray);
            }
            else {
                $means[$currency["id"]] = [$nullArray];
            }
        }

        $incomeOutcome = auth()->user()->income
            ->merge(auth()->user()->outcome)
            ->sortBy("updated_at");

        $titles = $incomeOutcome
            ->unique("title")
            ->values()
            ->map(fn ($item) => $item["title"]);

        $incomeOutcome = $incomeOutcome->last();

        $last = [
            "currency" => $this->getLastCurrency(),
            "category" => $incomeOutcome == null ? 0 : $incomeOutcome->category_id,
            "mean" => $incomeOutcome == null ? 0 : $incomeOutcome->mean_id
        ];

        return compact("currencies", "categories", "means", "titles", "last");
    }

    public function start()
    {
        // Get currencies
        $currencies = Currency::all();

        // Get means of payment
        $meansTemp = auth()->user()->meansOfPayment->map(function ($item) {
            return [$item["id"] => $item["name"]];
        });
        $means = [];
        foreach ($meansTemp as $meanTemp) {
            foreach ($meanTemp as $key => $val) {
                $means[$key] = $val;
            }
        }

        // Get categories
        $categoriesTemp = auth()->user()->categories->map(function ($item) {
            return [$item["id"] => $item["name"]];
        });
        $categories = [];
        foreach ($categoriesTemp as $categoryTemp) {
            foreach ($categoryTemp as $key => $val) {
                $categories[$key] = $val;
            }
        }

        $lastCurrency = $this->getLastCurrency();

        return response()->json(compact('currencies', 'means', 'categories', 'lastCurrency'));
    }

    public function all($viewType, Currency $currency)
    {
        $queryArray = [
            "user_id" => auth()->user()->id,
            "currency_id" => $currency->id
        ];

        $data = (
            $viewType == "income" ?
                Income::where($queryArray) :
                Outcome::where($queryArray)
            )
            ->select(
                "id", "date", "title",
                "amount", "price", "category_id",
                "mean_id", "currency_id"
            )
            ->orderBy("date", "DESC")
            ->orderBy("title")
            ->paginate(20);

        return response()->json(compact("data"));
    }

    public function create($viewType)
    {
        $data = $this->getData($viewType);

        return response()->json(compact("data"));
    }

    public function store($viewType)
    {
        $directory = "data.*";
        $data = request()->validate([
            "$directory.date" => ["required", "date", new CorrectDateIO],
            "$directory.title" => ["required", "string", "max:64"],
            "$directory.amount" => ["required", "numeric", "max:1e6", "min:0", "not_in:0,1e6"],
            "$directory.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "$directory.currency_id" => ["required", "integer", "exists:currencies,id"],
            "$directory.category_id" => ["present", "integer", new ValidCategoryMean($viewType)],
            "$directory.mean_id" => ["present", "integer", new ValidCategoryMean($viewType)]
        ])["data"];

        foreach ($data as $key => $item) {
            $data[$key] = array_merge(
                $data[$key],
                [
                    "user_id" => auth()->user()->id,
                    "category_id" => $data[$key]["category_id"] == 0 ?
                        null : $data[$key]["category_id"],
                    "mean_id" => $data[$key]["mean_id"] == 0 ?
                    null : $data[$key]["mean_id"]
                ]
            );
        }

        if ($viewType == "income") {
            foreach ($data as $item) {
                Income::create($item);
            }
        }
        else {
            foreach ($data as $item) {
                Outcome::create($item);
            }
        }

        return response("", 200);
    }

    public function edit($viewType, $incomeOutcome)
    {
        if (!intval($incomeOutcome)) {
            return abort(404);
        }

        $incomeOutcome = $viewType == "income" ?
            Income::findOrFail($incomeOutcome) :
            Outcome::findOrFail($incomeOutcome);

        $this->authorize("viewIncomeOutcome", $incomeOutcome);

        $data = array_merge(
            $this->getData($viewType),
            [
                "data" => collect($incomeOutcome)->forget(["created_at", "updated_at", "user_id"])
            ]
        );

        return response()->json(compact("data"));
    }

    public function update($viewType, $incomeOutcome)
    {
        if (!intval($incomeOutcome)) {
            return abort(404);
        }

        $directory = "data.*";
        $data = request()->validate([
            "$directory.date" => ["required", "date", new CorrectDateIO],
            "$directory.title" => ["required", "string", "max:64"],
            "$directory.amount" => ["required", "numeric", "max:1e6", "min:0", "not_in:0,1e6"],
            "$directory.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "$directory.currency_id" => ["required", "integer", "exists:currencies,id"],
            "$directory.category_id" => ["present", "integer", new ValidCategoryMean($viewType)],
            "$directory.mean_id" => ["present", "integer", new ValidCategoryMean($viewType)]
        ])["data"][0];

        $incomeOutcome = $viewType == "income" ?
            Income::findOrFail($incomeOutcome) :
            Outcome::findOrFail($incomeOutcome);

        $this->authorize("viewIncomeOutcome", $incomeOutcome);

        $incomeOutcome->update(array_merge(
            $data,
            [
                "category_id" => $data["category_id"] == 0 ?
                    null : $data["category_id"],
                "mean_id" => $data["mean_id"] == 0 ?
                    null : $data["mean_id"],
                "user_id" => auth()->user()->id
            ]
        ));

        return response()->json([
            "data" => collect($incomeOutcome)->except(["user_id", "created_at", "updated_at"])
        ]);
    }

    public function delete($viewType, $incomeOutcome)
    {
        if (!intval($incomeOutcome)) {
            return abort(404);
        }

        $incomeOutcome = $viewType == "income" ?
            Income::findOrFail($incomeOutcome) :
            Outcome::findOrFail($incomeOutcome);

        $this->authorize("viewIncomeOutcome", $incomeOutcome);

        $incomeOutcome->delete();

        return response("", 200);
    }
}
