<?php

namespace App\Http\Controllers\WebApi;

use App\Currency;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SummaryController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function getData()
    {
        $currencies = $this->getCurrencies();
        $categories = auth()->user()->categories;
        $means = auth()->user()->meansOfPayment;

        $meansToShow = $means->where("count_to_summary", true)->pluck("id")->toArray();
        $categoriesToShow = $categories->where("count_to_summary", true)->pluck("id")->toArray();

        $income = auth()->user()->income
            ->map(function ($item) {
                $item["value"] = $item["amount"] * $item["price"];
                return collect($item)->only("value", "category_id", "mean_id", "currency_id", "date")->toArray();
            });

        $outcome = auth()->user()->outcome
            ->map(function ($item) {
                $item["value"] = $item["amount"] * $item["price"];
                return collect($item)->only("value", "category_id", "mean_id", "currency_id", "date")->toArray();
            });

        $incomeByMeans = $income
            ->whereIn("mean_id", $meansToShow)
            ->groupBy("mean_id")
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

        $outcomeByMeans = $outcome
            ->whereIn("mean_id", $meansToShow)
            ->groupBy("mean_id")
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

        $balanceByMeans = $incomeByMeans;
        foreach ($outcomeByMeans as $mean => $balance) {
            if (array_key_exists($mean, $balanceByMeans)) {
                $balanceByMeans[$mean] -= $balance;
            }
            else {
                $balanceByMeans[$mean] = -$balance;
            }
        }

        $balanceByCategories = $outcome
            ->whereIn("category_id", $categoriesToShow)
            ->groupBy("category_id");

        foreach ($balanceByCategories as $categoryID => $i) {
            foreach ($balanceByCategories[$categoryID] as $key => $value) {
                $startTime = $categories->where("id", $categoryID)->first()["start_date"];
                $endTime = $categories->where("id", $categoryID)->first()["end_date"];

                if (
                    $startTime != null && strtotime($value["date"]) < $startTime ||
                    $endTime != null && strtotime($value["date"]) > $endTime
                ) {
                    unset($balanceByCategories[$categoryID][$key]);
                }
            }
        }

        $balanceByCategories = $balanceByCategories
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

        $finalData = [];
        $foundIDs = [];
        foreach ($balanceByMeans as $meanID => $balance) {
            $foundMean = $means->where("id", $meanID)->first();

            array_push($finalData, [
                "name" => $foundMean->name,
                "currency_id" => $foundMean->currency_id,
                "balance" => $balance + $foundMean->first_entry_amount
            ]);

            array_push($foundIDs, $foundMean->id);
        }

        foreach ($means->whereNotIn("id", $foundIDs) as $mean) {
            array_push($finalData, [
                "name" => $mean->name,
                "currency_id" => $mean->currency_id,
                "balance" => $mean->first_entry_amount * 1
            ]);
        }

        foreach ($balanceByCategories as $categoryID => $balance) {
            $foundCategory = $categories->where("id", $categoryID)->first();
            array_push($finalData, [
                "name" => $foundCategory->name,
                "currency_id" => $foundCategory->currency_id,
                "balance" => $balance
            ]);
        }

        $finalData = collect($finalData)
            ->sortByDesc("balance")
            ->groupBy("currency_id");

        $lastCurrency = $this->getLastCurrency();

        return response()->json(compact("currencies", "finalData", "lastCurrency"));
    }
}
