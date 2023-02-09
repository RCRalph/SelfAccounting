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
            "routes" => [
                "/",
                "/dashboard"
            ]
        ],
        [
            "name" => "Income by categories",
            "routes" => [
                "/",
                "/dashboard",
                "/income"
            ]
        ],
        [
            "name" => "Outcome by categories",
            "routes" => [
                "/",
                "/dashboard",
                "/outcome"
            ]
        ],
        [
            "name" => "Income by accounts",
            "routes" => [
                "/",
                "/dashboard",
                "/income"
            ]
        ],
        [
            "name" => "Outcome by accounts",
            "routes" => [
                "/",
                "/dashboard",
                "/outcome"
            ]
        ]
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
            $chartInDB = Chart::firstOrCreate(["name" => $chart["name"]]);

            $chartInDB->routes()->whereNotIn("route", $chart["routes"])->delete();
            foreach ($chart["routes"] as $route) {
                $chartInDB->routes()->firstOrCreate([ "route" => $route ]);
            }
        }
    }
}
