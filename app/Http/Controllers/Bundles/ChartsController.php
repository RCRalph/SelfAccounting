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
                    "directory" => "balance-monitor"
                ]
            ],
            [
                [
                    "title" => "Income by categories",
                    "description" => "See how your income splits into categories.",
                    "directory" => "income-by-categories"
                ],
                [
                    "title" => "Outcome by categories",
                    "description" => "See how your outcome splits into categories.",
                    "directory" => "outcome-by-categories"
                ]
            ],
            [
                [
                    "title" => "Income by means of payment",
                    "description" => "See how your income splits into means of payment.",
                    "directory" => "income-by-means-of-payment"
                ],
                [
                    "title" => "Outcome by means of payment",
                    "description" => "See how your outcome splits into means of payment.",
                    "directory" => "outcome-by-means-of-payment"
                ]
            ]
		];

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

    public function incomeByCategories()
    {
        $pageData = $this->getDataForPageRender();
        $title = "Income by categories";

        return view("bundles.charts.io-by-type", compact("pageData", "title"));
    }

    public function outcomeByCategories()
    {
        $pageData = $this->getDataForPageRender();
        $title = "Outcome by categories";

        return view("bundles.charts.io-by-type", compact("pageData", "title"));
    }

    public function incomeByMeans()
    {
        $pageData = $this->getDataForPageRender();
        $title = "Income by means of payment";

        return view("bundles.charts.io-by-type", compact("pageData", "title"));
    }

    public function outcomeByMeans()
    {
        $pageData = $this->getDataForPageRender();
        $title = "Outcome by means of payment";

        return view("bundles.charts.io-by-type", compact("pageData", "title"));
    }
}
