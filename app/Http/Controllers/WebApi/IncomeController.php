<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Currency;
use App\Income;
use App\MeanOfPayment;

use App\Rules\CorrectDateIO_One;
use App\Rules\ValidCategoryMean;

class IncomeController extends Controller
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

        return response()->json(compact('currencies', 'means', 'categories'));
    }

    public function getIncome()
    {
        $currency_id = request()->validate([
            "currency_id" => ["required", "exists:currencies,id"]
        ])["currency_id"];

        $data = Income::where([
            "user_id" => auth()->user()->id,
            "currency_id" => $currency_id
            ])
            ->select("id", "date", "title", "amount", "price", "category_id", "mean_id", "currency_id")
            ->orderBy("date", "DESC")
            ->orderBy("title")
            ->paginate(20);

        return response()->json(compact("data"));
    }

    public function getEditData(Income $income)
    {
        $this->authorize("view", $income);

        $income = collect($income)->except(["user_id", "created_at", "updated_at"]);
        $currencies = Currency::all();

        $categories = auth()->user()->categories->where("income_category", true)->map(function($item) {
            return collect($item)->only(["id", "name", "currency_id"])->toArray();
        })->groupBy("currency_id");

        $means = auth()->user()->meansOfPayment->where("income_mean", true)->map(function($item) {
            return collect($item)->only(["id", "name", "currency_id"])->toArray();
        })->groupBy("currency_id")->toArray();

        $titles = auth()->user()->income->unique("title")->values()->map(function($item) {
            return $item["title"];
        });

        $mean = MeanOfPayment::find($income["mean_id"]);

        if (!$mean) {
            $income["mean_id"] = null;
        }

        $firstEntryDate = !$mean ? null
            : $mean->first_entry_date;

        return response()->json(compact("income", "currencies", "categories", "means", "titles", "firstEntryDate"));
    }

    public function updateIncome()
    {
        $data = request()->validate([
            "date" => ["required", "date", new CorrectDateIO_One],
            "title" => ["required", "string", "max:64"],
            "amount" => ["required", "numeric"],
            "price" => ["required", "numeric"],
            "currency_id" => ["required", "exists:currencies,id"],
            "category_id" => ["present", "nullable", new ValidCategoryMean],
            "mean_id" => ["present", "nullable", new ValidCategoryMean],
            "id" => ["required", "exists:incomes,id"]
        ]);

        $this->authorize("update", $data);

        $income = Income::find($data["id"]);
        $income->update($data);

        return response()->json([
            "data" => collect($income)->except(["user_id", "created_at", "updated_at"])
        ]);
    }

    public function deleteIncome(Income $income)
    {
        $this->authorize("view", $income);

        Income::find($income->id)->delete();

        return response()->json([
            "status" => "success"
        ]);
    }
}
