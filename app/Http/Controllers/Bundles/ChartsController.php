<?php

namespace App\Http\Controllers\Bundles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChartsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "bundle:charts"]);
    }

    public function index()
    {
        $pageData = $this->getDataForPageRender();

        $charts = [
            [
                [
                    "title" => "Balance monitor",
                    "description" => "This chart shows how your balance has progressed overtime.",
                    "directory" => "balance-monitor",
                    "background" => [255, 213, 0]
                ]
            ],
            [
                [
                    "title" => "Income by categories",
                    "description" => "See how your income splits into categories.",
                    "directory" => "income-by-categories",
                    "background" => [136, 3, 252]
                ],
                [
                    "title" => "Outcome by categories",
                    "description" => "See how your outcome splits into categories.",
                    "directory" => "outcome-by-categories",
                    "background" => [252, 90, 3]
                ]
            ],
            [
                [
                    "title" => "Income by means of payment",
                    "description" => "See how your income splits into means of payment.",
                    "directory" => "income-by-means-of-payment",
                    "background" => [3, 252, 111]
                ],
                [
                    "title" => "Outcome by means of payment",
                    "description" => "See how your outcome splits into means of payment.",
                    "directory" => "outcome-by-means-of-payment",
                    "background" => [206, 252, 3]
                ]
            ]
		];

		foreach ($charts as $groupKey => $group) {
            foreach ($group as $chartKey => $chart) {
                $charts[$groupKey][$chartKey]["color"] = $this->getTextColorOnBackgroundRGB($chart["background"]);
            }
        }

        return view("bundles.charts.index", compact("pageData", "charts"));
    }

    public function presence()
    {
        $pageData = $this->getDataForPageRender();

        return view("bundles.charts.presence", compact("pageData"));
    }

    public function balanceMonitor()
    {
        $pageData = $this->getDataForPageRender();

        return view("bundles.charts.balance-monitor", compact("pageData"));
    }
}
