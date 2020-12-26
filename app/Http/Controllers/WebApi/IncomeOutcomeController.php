<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Currency;
use App\Income;
use App\Outcome;
use App\MeanOfPayment;

use App\Rules\CorrectDateIO_One;
use App\Rules\ValidCategoryMean;
use App\Http\Middleware\IncomeOutcome;

class IncomeOutcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", IncomeOutcome::class]);
    }

    public function start()
    {
        // Get currencies
        $currencies = Currency::all();

        // Get means of payment
        $meansTemp = auth()->user()->meansOfPayment->map(function($item) {
            return [$item["id"] => $item["name"]];
        });
        $means = [];
        foreach ($meansTemp as $meanTemp) {
            foreach ($meanTemp as $key => $val) {
                $means[$key] = $val;
            }
        }

        // Get categories
        $categoriesTemp = auth()->user()->categories->map(function($item) {
            return [$item["id"] => $item["name"]];
        });
        $categories = [];
        foreach ($categoriesTemp as $categoryTemp) {
            foreach ($categoryTemp as $key => $val) {
                $categories[$key] = $val;
            }
        }

        $lastCurrency = auth()->user()->income
            ->concat(auth()->user()->outcome)
            ->sortBy("updated_at")->last();
        $lastCurrency = $lastCurrency == null ? 1 : $lastCurrency->currency_id;

        return response()->json(compact('currencies', 'means', 'categories', 'lastCurrency'));
    }

    public function get($viewType, Currency $currency)
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

    public function getCreate($viewType)
    {
        $currencies = Currency::all();

        $categories = auth()->user()->categories
            ->where($viewType . "_category", true)
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id"]))
            ->groupBy("currency_id");

        $means = auth()->user()->meansOfPayment
            ->where($viewType . "_mean", true)
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id", "first_entry_date"]))
            ->groupBy("currency_id");

        $incomeOutcome = $viewType == "income" ?
            auth()->user()->income :
            auth()->user()->outcome;

        $titles = $incomeOutcome
            ->unique("title")
            ->values()
            ->map(fn ($item) => $item["title"]);

        $lastCurrency = $incomeOutcome
            ->concat(
                $viewType == "outcome" ?
                auth()->user()->income :
                auth()->user()->outcome
            )
            ->sortBy("updated_at")->last();
        $lastCurrency = $lastCurrency == null ? 1 : $lastCurrency->currency_id;

        $lastIncomeOutcome = $incomeOutcome
            ->sortBy("updated_at")->last();

        $lastMean = $lastIncomeOutcome == null ? null : $lastIncomeOutcome->mean_id;
        $lastCategory = $lastIncomeOutcome == null ? null : $lastIncomeOutcome->category_id;

        return response()->json(compact(
            "currencies", "categories", "means", "lastCurrency", "lastCategory", "lastMean", "titles"
        ));
    }

    public function storeOne($viewType)
	{
		$data = request()->validate([
            "date" => ["required", "date", new CorrectDateIO_One],
            "title" => ["required", "string", "max:64"],
            "amount" => ["required", "numeric"],
            "price" => ["required", "numeric"],
            "currency_id" => ["required", "integer", "exists:currencies,id"],
            "category_id" => ["present", "nullable", new ValidCategoryMean],
            "mean_id" => ["present", "nullable", new ValidCategoryMean]
        ]);

        $data = array_merge(
            $data,
            [
                "user_id" => auth()->user()->id,

                "category_id" => $data["category_id"] == "null" ?
                    null : $data["category_id"],

                "mean_id" => $data["mean_id"] == "null" ?
                    null : $data["mean_id"]
            ]
        );

        if ($viewType == "income") {
            Income::create($data);
        }
        else {
            Outcome::create($data);
        }

        return response()->json([
            "status" => "success"
        ]);
    }

    public function getEdit($viewType, $incomeOutcome)
    {
        $incomeOutcome = $viewType == "income" ?
            Income::findOrFail($incomeOutcome) :
            Outcome::findOrFail($incomeOutcome);

        $this->authorize("viewIncomeOutcome", $incomeOutcome);

        $currencies = Currency::all();

        $income = collect($incomeOutcome)
            ->except(["user_id", "created_at", "updated_at"]);

        $categories = auth()->user()->categories
            ->where($viewType . "_category", true)
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id"]))
            ->groupBy("currency_id");

        $means = auth()->user()->meansOfPayment
            ->where($viewType . "_mean", true)
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id", "first_entry_date"]))
            ->groupBy("currency_id");

        $titles = ($viewType == "income" ?
            auth()->user()->income :
            auth()->user()->outcome)
            ->unique("title")
            ->values()
            ->map(fn ($item) => $item["title"]);

        return response()->json(compact("income", "currencies", "categories", "means", "titles"));
    }

    public function update($viewType, $incomeOutcome)
    {
        $incomeOutcome = $viewType == "income" ?
            Income::findOrFail($incomeOutcome) :
            Outcome::findOrFail($incomeOutcome);

        $data = request()->validate([
            "date" => ["required", "date", new CorrectDateIO_One],
            "title" => ["required", "string", "max:64"],
            "amount" => ["required", "numeric"],
            "price" => ["required", "numeric"],
            "currency_id" => ["required", "integer", "exists:currencies,id"],
            "category_id" => ["present", "nullable", new ValidCategoryMean],
            "mean_id" => ["present", "nullable", new ValidCategoryMean]
        ]);

        $this->authorize("viewIncomeOutcome", $incomeOutcome);
        $incomeOutcome->update(array_merge(
            $data,
            [
                "category_id" => $data["category_id"] == "null" ?
                    null : $data["category_id"],
                "mean_id" => $data["mean_id"] == "null" ?
                    null : $data["mean_id"]
            ]
        ));

        return response()->json([
            "data" => collect($incomeOutcome)->except(["user_id", "created_at", "updated_at"])
        ]);
    }

    public function delete($viewType, $incomeOutcome)
    {
        $incomeOutcome = $viewType == "income" ?
            Income::findOrFail($incomeOutcome) :
            Outcome::findOrFail($incomeOutcome);

        $this->authorize("viewIncomeOutcome", $incomeOutcome);

        $incomeOutcome->delete();

        return response()->json([
            "status" => "success"
        ]);
    }
}
