<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Currency;
use App\Category;
use App\MeanOfPayment;

use App\Rules\DateBeforeOrEqualField;
use App\Rules\CorrectFirstEntryDate;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function categories(Currency $currency)
    {
        return auth()->user()->categories()
            ->select("id", "name", "income_category", "outcome_category", "show_on_charts", "count_to_summary", "start_date", "end_date")
            ->where("currency_id", $currency->id)
            ->orderBy("name")
            ->get();
    }

    private function means(Currency $currency)
    {
        return auth()->user()->meansOfPayment()
            ->select("id", "name", "income_mean", "outcome_mean", "show_on_charts", "count_to_summary", "first_entry_date", "first_entry_amount")
            ->where("currency_id", $currency->id)
            ->orderBy("name")
            ->get();
    }

    public function index(Currency $currency)
    {
        $categories = $this->categories($currency);
        $means = $this->means($currency);

        return response()->json(compact("categories", "means"));
    }

    public function getCategories(Currency $currency)
    {
        return response()->json($this->categories($currency));
    }

    public function createCategory()
    {
        $data = request()->validate([
            "currency_id" => ["required", "integer", "exists:currencies,id"],
            "name" => ["required", "string", "max:32"],
            "income_category" => ["required", "boolean"],
            "outcome_category" => ["required", "boolean"],
            "show_on_charts" => ["required", "boolean"],
            "count_to_summary" => ["required", "boolean"],
            "start_date" => ["present", "date", "nullable", new DateBeforeOrEqualField("end_date")],
            "end_date" => ["present", "date", "nullable"]
        ]);

        auth()->user()->categories()->create($data);

        return response("");
    }

    public function showCategory(Category $category)
    {
        $this->authorize("view", $category);

        return response()->json([ "data" => $category->makeHidden(["user_id", "currency_id", "created_at", "updated_at"]) ]);
    }

    public function updateCategory(Category $category)
    {
        $this->authorize("update", $category);

        $data = request()->validate([
            "name" => ["required", "string", "max:32"],
            "income_category" => ["required", "boolean"],
            "outcome_category" => ["required", "boolean"],
            "show_on_charts" => ["required", "boolean"],
            "count_to_summary" => ["required", "boolean"],
            "start_date" => ["present", "date", "nullable", new DateBeforeOrEqualField("end_date")],
            "end_date" => ["present", "date", "nullable"]
        ]);

        $category->update($data);

        return response("");
    }

    public function deleteCategory(Category $category)
    {
        $this->authorize("delete", $category);

        $category->delete();

        return response("");
    }

    public function getMeans(Currency $currency)
    {
        return response()->json($this->means($currency));
    }

    public function createMean()
    {
        $data = request()->validate([
            "currency_id" => ["required", "integer", "exists:currencies,id"],
            "name" => ["required", "string", "max:32"],
            "income_mean" => ["required", "boolean"],
            "outcome_mean" => ["required", "boolean"],
            "show_on_charts" => ["required", "boolean"],
            "count_to_summary" => ["required", "boolean"],
            "first_entry_date" => ["required", "date", "after_or_equal:1970-01-01"],
            "first_entry_amount" => ["required", "numeric", "min:-1e11", "max:1e11", "not_in:-1e11,1e11"]
        ]);

        auth()->user()->meansOfPayment()->create($data);

        return response("");
    }

    public function showMean(MeanOfPayment $mean)
    {
        $this->authorize("view", $mean);

        $mean = $mean->makeHidden(["user_id", "currency_id", "created_at", "updated_at"]);

        $minDate = auth()->user()->income()
            ->select("date")
            ->where("mean_id", $mean->id)
            ->union(auth()->user()->outcome()
                ->select("date")
                ->where("mean_id", $mean->id)
            )
            ->orderBy("date")
            ->first();

        $mean->max_date = $minDate->date ?? null;

        return response()->json([ "data" => $mean ]);
    }

    public function updateMean(MeanOfPayment $mean)
    {
        $this->authorize("update", $mean);

        $data = request()->validate([
            "name" => ["required", "string", "max:32"],
            "income_mean" => ["required", "boolean"],
            "outcome_mean" => ["required", "boolean"],
            "show_on_charts" => ["required", "boolean"],
            "count_to_summary" => ["required", "boolean"],
            "first_entry_date" => ["required", "date", "after_or_equal:1970-01-01", new CorrectFirstEntryDate($mean)],
            "first_entry_amount" => ["required", "numeric", "min:-1e11", "max:1e11", "not_in:-1e11,1e11"]
        ]);

        $mean->update($data);

        return response("");
    }

    public function deleteMean(MeanOfPayment $mean)
    {
        $this->authorize("delete", $mean);

        $mean->delete();

        return response("");
    }
}
