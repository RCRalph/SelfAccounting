<?php

namespace App\Http\Controllers\Bundles\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "bundle:report"]);
    }

    public function userReports()
    {
        $reports = auth()->user()->reports()
            ->select("id", "title")
            ->orderBy("id")
            ->paginate(10);

        return response()->json(compact("reports"));
    }

    public function sharedReports()
    {
        $reports = auth()->user()->sharedReports()
            ->select("id", "title")
            ->paginate(10);

        return response()->json(compact("reports"));
    }

    public function create()
    {
        $queryTypes = ["income", "outcome"];

        $currencies = $this->getCurrencies();
        $nullArray = [
            "id" => 0,
            "name" => "N/A"
        ];

        $categories = auth()->user()->categories
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id"]))
            ->groupBy("currency_id")
            ->toArray();

        foreach ($currencies as $currency) {
            if (isset($categories[$currency["id"]])) {
                array_unshift($categories[$currency["id"]], $nullArray);
            }
            else {
                $categories[$currency["id"]] = [$nullArray];
            }
        }

        $means = auth()->user()->meansOfPayment
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id"]))
            ->groupBy("currency_id")
            ->toArray();

        foreach ($currencies as $currency) {
            if (isset($means[$currency["id"]])) {
                array_unshift($means[$currency["id"]], $nullArray);
            }
            else {
                $means[$currency["id"]] = [$nullArray];
            }
        }


        $titles = auth()->user()->income
            ->merge(auth()->user()->outcome)
            ->unique("title")
            ->values()
            ->map(fn ($item) => $item["title"])
            ->sort()
            ->values();

        $lastCurrency = $this->getLastCurrency();

        return response()->json(compact("currencies", "categories", "means", "titles", "queryTypes", "lastCurrency"));
    }
}
