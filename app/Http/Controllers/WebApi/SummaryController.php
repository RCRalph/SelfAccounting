<?php

namespace App\Http\Controllers\WebApi;

use App\Currency;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SummaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getData()
    {
        $currencies = Currency::all();
        $categories = auth()->user()->categories;
        $means = auth()->user()->meansOfPayment;

        $meansToShow = $means->where("count_to_summary", true)->pluck("id")->toArray();
        $categoriesToShow = $categories->where("count_to_summary", true)->pluck("id")->toArray();

        $income = auth()->user()->income->map(function($item) {
            $item["value"] = $item["amount"] * $item["price"];
            return collect($item)->only("value", "category_id", "mean_id", "currency_id", "date")->toArray();
        });

        $outcome = auth()->user()->outcome->map(function($item) {
            $item["value"] = $item["amount"] * $item["price"];
            return collect($item)->only("value", "category_id", "mean_id", "currency_id", "date")->toArray();
        });

        $incomeByMeans = $income
            ->whereIn("mean_id", $meansToShow)
            ->groupBy("mean_id")
            ->map(function($item) {
                $sum = 0;

                foreach ($item as $data) {
                    $sum += $data["value"];
                }

                return $sum;
            })->toArray();

        $outcomeByMeans = $outcome
            ->whereIn("mean_id", $meansToShow)
            ->groupBy("mean_id")
            ->map(function($item) {
                $sum = 0;

                foreach ($item as $data) {
                    $sum += $data["value"];
                }

                return $sum;
            })->toArray();

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
                $toUnset = false;
                if (strtotime($value["date"]) < strtotime($categories[$categoryID]["start_date"]) &&
                    $categories[$categoryID]["start_date"] != null) {

                    $toUnset = true;
                }
                if (strtotime($value["date"]) > strtotime($categories[$categoryID]["end_date"]) &&
                    $categories[$categoryID]["end_date"] != null) {

                    $toUnset = true;
                }

                if ($toUnset) {
                    unset($balanceByCategories[$categoryID][$key]);
                }
            }
        }

        $balanceByCategories = $balanceByCategories->map(function($item) {
            $sum = 0;

            foreach ($item as $data) {
                $sum += $data["value"];
            }

            return $sum;
        })->toArray();

        $finalData = [];
        foreach ($balanceByMeans as $meanID => $balance) {
            $foundMean = $means->where("id", $meanID)->first();
            array_push($finalData, [
                "name" => $foundMean->name,
                "currency_id" => $foundMean->currency_id,
                "balance" => $balance
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

        $finalData = collect($finalData)->groupBy("currency_id");
        
        $lastCurrency = $income->concat($outcome)->sortBy("updated_at")->last();
        $lastCurrency = $lastCurrency == null ? 1 : $lastCurrency->currency_id;

        return response()->json(compact('currencies', 'finalData', 'lastCurrency'));
    }
}
