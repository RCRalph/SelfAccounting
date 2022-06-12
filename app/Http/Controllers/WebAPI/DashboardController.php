<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Currency;
use App\Chart;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function getLast30DaysBalance($income, $outcome, $meansToShow, $categoriesToShow)
    {
        $incomeToSum = $income
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->whereIn("mean_id", $meansToShow)
            ->sum("value");

        $outcomeToSum = $outcome
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->whereIn("mean_id", $meansToShow)
            ->sum("value");

        $outcomeCategorySum = $outcome
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->whereIn("category_id", $categoriesToShow)
            ->sum("value");

        return [
            "income" => $incomeToSum,
            "outcome" => $outcomeToSum - $outcomeCategorySum
        ];
    }

    public function index(Currency $currency)
    {
        $categories = auth()->user()->categories
            ->where("currency_id", $currency->id);

        $means = auth()->user()->meansOfPayment
            ->where("currency_id", $currency->id);

        $meansToShow = $means
            ->where("count_to_summary", true)
            ->pluck("id")->toArray();

        $categoriesToShow = $categories
            ->where("count_to_summary", true)
            ->pluck("id")->toArray();

        $income = auth()->user()->income
            ->where("currency_id", $currency->id)
            ->map(function ($item) {
                $item["value"] = round($item["amount"] * $item["price"], 2);

                return collect($item)
                    ->only("value", "category_id", "mean_id", "date")
                    ->toArray();
            });

        $outcome = auth()->user()->outcome
            ->where("currency_id", $currency->id)
            ->map(function ($item) {
                $item["value"] = round($item["amount"] * $item["price"], 2);

                return collect($item)
                    ->only("value", "category_id", "mean_id", "date")
                    ->toArray();
            });

        $currentBalance = $this->getBalance($income, $outcome, $means, $categories, $meansToShow, $categoriesToShow);
        $last30Days = $this->getLast30DaysBalance($income, $outcome, $meansToShow, $categoriesToShow);

        $charts = Chart::all();

        return response()->json(compact("currentBalance", "last30Days", "charts"));
    }
}
