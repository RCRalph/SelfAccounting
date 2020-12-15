<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Currency;
use App\Outcome;
use App\MeanOfPayment;

use App\Rules\CorrectDateIO_One;
use App\Rules\ValidCategoryMean;

class OutcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

        $lastCurrency = auth()->user()->outcome->concat(auth()->user()->income)->sortBy("updated_at")->last();
        $lastCurrency = $lastCurrency == null ? 1 : $lastCurrency->currency_id;

        return response()->json(compact('currencies', 'means', 'categories', 'lastCurrency'));
    }

    public function getOutcome()
    {
        $currency_id = request()->validate([
            "currency_id" => ["required", "exists:currencies,id"]
        ])["currency_id"];

        $data = Outcome::where([
            "user_id" => auth()->user()->id,
            "currency_id" => $currency_id
            ])
            ->select("id", "date", "title", "amount", "price", "category_id", "mean_id", "currency_id")
            ->orderBy("date", "DESC")
            ->orderBy("title")
            ->paginate(20);

        return response()->json(compact("data"));
    }

    public function getCreateData()
    {
        $currencies = Currency::all();

        $categories = auth()->user()->categories->where("outcome_category", true)->map(function($item) {
            return collect($item)->only(["id", "name", "currency_id"])->toArray();
        })->groupBy("currency_id")->toArray();

        $means = auth()->user()->meansOfPayment->where("outcome_mean", true)->map(function($item) {
            return collect($item)->only(["id", "name", "currency_id", "first_entry_date"])->toArray();
        })->groupBy("currency_id")->toArray();

        $outcome = auth()->user()->outcome;

        $titles = $outcome->unique("title")->values()->map(function($item) {
            return $item["title"];
        });

        $lastCurrency = $outcome->concat(auth()->user()->income)->sortBy("updated_at")->last();
        $lastCurrency = $lastCurrency == null ? 1 : $lastCurrency->currency_id;

        $lastOutcome = $outcome->sortBy("updated_at")->last();
        $lastMean = $lastOutcome == null ? null : $lastOutcome->mean_id;
        $lastCategory = $lastOutcome == null ? null : $lastOutcome->category_id;

        return response()->json(compact(
            "currencies", "categories", "means", "lastCurrency", "lastCategory", "lastMean", "titles"
        ));
    }

    public function storeOne()
	{
		$data = request()->validate([
            "date" => ["required", "date", new CorrectDateIO_One],
            "title" => ["required", "string", "max:64"],
            "amount" => ["required", "numeric"],
            "price" => ["required", "numeric"],
            "currency_id" => ["required", "exists:currencies,id"],
            "category_id" => ["present", "nullable", new ValidCategoryMean],
            "mean_id" => ["present", "nullable", new ValidCategoryMean]
        ]);

        Outcome::create(array_merge(
            $data,
            [
                "user_id" => auth()->user()->id,
                "category_id" => $data["category_id"] == "null" ? null : $data["category_id"],
                "mean_id" => $data["mean_id"] == "null" ? null : $data["mean_id"]
            ]
        ));

        return response()->json([
            "status" => "success"
        ]);
    }

    public function getEditData(Outcome $outcome)
    {
        $this->authorize("view", $outcome);

        $outcome = collect($outcome)->except(["user_id", "created_at", "updated_at"]);
        $currencies = Currency::all();

        $categories = auth()->user()->categories->where("outcome_category", true)->map(function($item) {
            return collect($item)->only(["id", "name", "currency_id"])->toArray();
        })->groupBy("currency_id");

        $means = auth()->user()->meansOfPayment->where("outcome_mean", true)->map(function($item) {
            return collect($item)->only(["id", "name", "currency_id", "first_entry_date"])->toArray();
        })->groupBy("currency_id");

        $titles = auth()->user()->outcome->unique("title")->values()->map(function($item) {
            return $item["title"];
        });

        return response()->json(compact("outcome", "currencies", "categories", "means", "titles"));
    }

    public function updateOutcome()
    {
        $data = request()->validate([
            "date" => ["required", "date", new CorrectDateIO_One],
            "title" => ["required", "string", "max:64"],
            "amount" => ["required", "numeric"],
            "price" => ["required", "numeric"],
            "currency_id" => ["required", "exists:currencies,id"],
            "category_id" => ["present", "nullable", new ValidCategoryMean],
            "mean_id" => ["present", "nullable", new ValidCategoryMean],
            "id" => ["required", "exists:outcomes,id"]
        ]);

        $outcome = Outcome::find($data["id"]);

        $this->authorize("update", $outcome);
        $outcome->update(array_merge(
            $data,
            [
                "category_id" => $data["category_id"] == "null" ? null : $data["category_id"],
                "mean_id" => $data["mean_id"] == "null" ? null : $data["mean_id"]
            ]
        ));

        return response()->json([
            "data" => collect($outcome)->except(["user_id", "created_at", "updated_at"])
        ]);
    }

    public function deleteOutcome(Outcome $outcome)
    {
        $this->authorize("view", $outcome);

        Outcome::find($outcome->id)->delete();

        return response()->json([
            "status" => "success"
        ]);
    }
}
