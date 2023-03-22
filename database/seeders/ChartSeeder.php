<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Chart;

class ChartSeeder extends Seeder
{
    private $charts = [
        [
            "name" => "Balance history",
            "type" => "line",
            "routes" => [
                "/",
                "/dashboard"
            ]
        ],
        [
            "name" => "Income by categories",
            "type" => "doughnut",
            "routes" => [
                "/",
                "/dashboard",
                "/income"
            ]
        ],
        [
            "name" => "Expenses by categories",
            "type" => "doughnut",
            "routes" => [
                "/",
                "/dashboard",
                "/expenses"
            ]
        ],
        [
            "name" => "Income by accounts",
            "type" => "doughnut",
            "routes" => [
                "/",
                "/dashboard",
                "/income"
            ]
        ],
        [
            "name" => "Expenses by accounts",
            "type" => "doughnut",
            "routes" => [
                "/",
                "/dashboard",
                "/expenses"
            ]
        ],
        [
            "name" => "Transfers by source accounts",
            "type" => "doughnut",
            "routes" => [
                "/",
                "/dashboard",
                "/transfers"
            ]
        ],
        [
            "name" => "Transfers by target accounts",
            "type" => "doughnut",
            "routes" => [
                "/",
                "/dashboard",
                "/transfers"
            ]
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Chart::whereNotIn("name", array_column($this->charts, "name"))->delete();

        foreach ($this->charts as $chart) {
            $chartInDB = Chart::updateOrCreate(
                [ "name" => $chart["name"] ],
                [ "type" => $chart["type"] ]
            );

            $chartInDB->routes()->whereNotIn("route", $chart["routes"])->delete();
            foreach ($chart["routes"] as $route) {
                $chartInDB->routes()->firstOrCreate([ "route" => $route ]);
            }
        }
    }
}
