<?php

namespace App\Http\Controllers\Bundles\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\CorrectCategoryMeanID;

use App\Currency;
use App\Category;
use App\MeanOfPayment;

class ChartsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "bundle:charts"]);
    }

    public function getPresence()
    {
        $currencies = $currencies = Currency::all()
            ->map(fn ($item) => $item->only("id", "ISO"));

        $categories = auth()->user()->categories
            ->map(fn ($item) => $item->only(
                "id", "name", "income_category", "outcome_category", "show_on_charts", "currency_id"
            ))
            ->groupBy("currency_id")
            ->toArray();

        $means = auth()->user()->meansOfPayment
            ->map(fn ($item) => $item->only(
                "id", "name", "income_mean", "outcome_mean", "show_on_charts", "currency_id"
            ))
            ->groupBy("currency_id")
            ->toArray();

        foreach ($currencies as $currency) {
            if (!isset($categories[$currency["id"]])) {
                $categories[$currency["id"]] = [];
            }

            if (!isset($means[$currency["id"]])) {
                $means[$currency["id"]] = [];
            }
        }

        $lastCurrency = $this->getLastCurrency();

        return response()->json(compact("currencies", "categories", "means", "lastCurrency"));
    }

    public function updateCategories()
    {
        $data = request()->validate([
            "data.*" => ["required", "integer", "not_in:0", new CorrectCategoryMeanID("category")]
        ]);

        $data = isset($data["data"]) ? $data["data"] : [];

        Category::whereIn("id", $data)->update([
            "show_on_charts" => true
        ]);

        Category::whereNotIn("id", $data)->update([
            "show_on_charts" => false
        ]);

        return response("", 200);
    }

    public function updateMeans()
    {
        $data = request()->validate([
            "data.*" => ["required", "integer", "not_in:0", new CorrectCategoryMeanID("mean")]
        ]);

        $data = isset($data["data"]) ? $data["data"] : [];

        MeanOfPayment::whereIn("id", $data)->update([
            "show_on_charts" => true
        ]);

        MeanOfPayment::whereNotIn("id", $data)->update([
            "show_on_charts" => false
        ]);

        return response("", 200);
    }
}
