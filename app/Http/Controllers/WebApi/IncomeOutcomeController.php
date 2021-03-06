<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Currency;
use App\Income;
use App\Outcome;
use App\MeanOfPayment;
use App\Cash;

use App\Rules\CorrectDateIO;
use App\Rules\ValidCategoryMean;
use App\Rules\HasEnoughCash;
use App\Rules\CorrectExchangeDate;
use App\Rules\ValidExchangeCategoryMean;
use App\Rules\HasEnoughExchangeCash;
use App\Http\Middleware\IncomeOutcome;

class IncomeOutcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", IncomeOutcome::class]);
    }

    private function getData($viewType)
    {
        $currencies = $this->getCurrencies();

        // Get categories
        $categories = auth()->user()->categories
            ->where($viewType . "_category", true)
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id"]))
            ->groupBy("currency_id")
            ->toArray();

        // Get means
        $means = auth()->user()->meansOfPayment
            ->where($viewType . "_mean", true)
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id", "first_entry_date"]))
            ->groupBy("currency_id")
            ->toArray();

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
            "category" => $incomeOutcome == null ? null : $incomeOutcome->category_id,
            "mean" => $incomeOutcome == null ? null : $incomeOutcome->mean_id
        ];

        $hasCashBundle = $this->hasBundle("cashan");
        if ($hasCashBundle) {
            $cash = $this->getCash();
            $cashMeans = $this->getCashMeans();
            $usersCash = auth()->user()->cash
                ->map(fn ($item) => [$item->id => $item->pivot->amount])
                ->mapWithKeys(fn ($item) => $item);
        }

        return array_merge(
            compact("currencies", "categories", "means", "titles", "last"),
            $hasCashBundle ? compact("cash", "cashMeans", "usersCash") : []
        );
    }

    public function start()
    {
        // Get currencies
        $currencies = $this->getCurrencies();

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

        return response()->json(compact("currencies", "means", "categories", "lastCurrency"));
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
            ->orderBy("amount")
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
            // Validate data
            "$directory.date" => ["required", "date", new CorrectDateIO],
            "$directory.title" => ["required", "string", "max:64"],
            "$directory.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "$directory.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "$directory.currency_id" => ["required", "integer", "exists:currencies,id"],
            "$directory.category_id" => ["present", "nullable", "integer", new ValidCategoryMean($viewType)],
            "$directory.mean_id" => ["present", "nullable", "integer", new ValidCategoryMean($viewType)],

            // Validate cash
            "cash" => ["nullable", "array"],
            "cash.*.amount" => ["required", "integer", "min:1", "max:9223372036854775807", new HasEnoughCash($viewType)],
            "cash.*.id" => ["required", "distinct", "exists:cash,id"]
        ]);
        if (isset($data["cash"])) {
            $cash = $data["cash"];
        }
        $data = $data["data"];

        if ($viewType == "income") {
            foreach ($data as $item) {
                auth()->user()->income()->create($item);
            }
        }
        else {
            foreach ($data as $item) {
                auth()->user()->outcome()->create($item);
            }
        }

        if ($this->hasBundle("cashan") && isset($cash)) {
            foreach ($cash as $cashToInsert) {
                $foundCash = auth()->user()->cash()->find($cashToInsert["id"]);
                if ($foundCash) {
                    $pivotAmount = $foundCash->pivot->amount + ($cashToInsert["amount"] * ($viewType == "income" ? 1 : -1));

                    auth()->user()->cash()->updateExistingPivot(
                        $cashToInsert["id"],
                        ["amount" => $pivotAmount]
                    );
                }
                else {
                    auth()->user()->cash()->attach(
                        $cashToInsert["id"],
                        ["amount" => $cashToInsert["amount"]]  // Don't have to check for outcome because we already know that it's about income only from validation
                    );
                }
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
            "$directory.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "$directory.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "$directory.currency_id" => ["required", "integer", "exists:currencies,id"],
            "$directory.category_id" => ["present", "nullable", "integer", new ValidCategoryMean($viewType)],
            "$directory.mean_id" => ["present", "nullable", "integer", new ValidCategoryMean($viewType)]
        ])["data"][0];

        $incomeOutcome = $viewType == "income" ?
            Income::findOrFail($incomeOutcome) :
            Outcome::findOrFail($incomeOutcome);

        $this->authorize("viewIncomeOutcome", $incomeOutcome);

        $incomeOutcome->update($data);

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

    public function exchange()
    {
        $data = request()->validate([
            "data" => ["required", "array"],
            "data.date" => ["required", "date", new CorrectExchangeDate],
            "data.title" => ["required", "string", "max:64"],

            "income" => ["required", "array"],
            "income.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "income.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "income.currency_id" => ["required", "integer", "exists:currencies,id"],
            "income.category_id" => ["present", "nullable", "integer", new ValidExchangeCategoryMean("income")],
            "income.mean_id" => ["present", "nullable", "integer", "different:outcome.mean_id", new ValidExchangeCategoryMean("income")],

            "outcome" => ["required", "array"],
            "outcome.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "outcome.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "outcome.currency_id" => ["required", "integer", "exists:currencies,id"],
            "outcome.category_id" => ["present", "nullable", "integer", new ValidExchangeCategoryMean("outcome")],
            "outcome.mean_id" => ["present", "nullable", "integer", "different:income.mean_id", new ValidExchangeCategoryMean("outcome")],

            "cash" => ["nullable", "array"],
            "cash.*.amount" => ["required", "integer", "min:1", "max:9223372036854775807", new HasEnoughExchangeCash],
            "cash.*.id" => ["required", "distinct", "exists:cash,id"]
        ]);

        auth()->user()->income()->create(array_merge(
            $data["income"],
            [
                "date" => $data["data"]["date"],
                "title" => $data["data"]["title"]
            ]
        ));

        auth()->user()->outcome()->create(array_merge(
            $data["outcome"],
            [
                "date" => $data["data"]["date"],
                "title" => $data["data"]["title"]
            ]
        ));

        if ($this->hasBundle("cashan") && isset($data["cash"])) {
            $cashMeans = $this->getCashMeans();
            $outcomeCurrency = $data["outcome"]["currency_id"];
            $outcomeMean = $data["outcome"]["mean_id"];

            foreach ($data["cash"] as $cashToInsert) {
                $foundCash = auth()->user()->cash()->find($cashToInsert["id"]);
                $isOutcome = $cashMeans[$outcomeCurrency] == $outcomeMean && Cash::find($cashToInsert["id"])->currency_id == $outcomeCurrency;

                if ($foundCash) {
                    $pivotAmount = $foundCash->pivot->amount + ($cashToInsert["amount"] * ($isOutcome ? -1 : 1));

                    auth()->user()->cash()->updateExistingPivot(
                        $cashToInsert["id"],
                        ["amount" => $pivotAmount]
                    );
                }
                else {
                    auth()->user()->cash()->attach(
                        $cashToInsert["id"],
                        ["amount" => $cashToInsert["amount"]] // Don't have to check for outcome because we already know that it's about income only from validation
                    );
                }
            }
        }

        return response("", 200);
    }
}
