<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Chart;

class ChartSeeder extends Seeder
{
    private $charts = [
        [
            "name" => "Balance history",
            "route" => "/"
        ],
        [
            "name" => "Income by categories",
            "route" => "/"
        ],
        [
            "name" => "Income by means of payment",
            "route" => "/"
        ],
        [
            "name" => "Outcome by categories",
            "route" => "/"
        ],
        [
            "name" => "Outcome by means of payment",
            "route" => "/"
        ],
        [
            "name" => "Balance history",
            "route" => "/dashboard"
        ],
        [
            "name" => "Income by categories",
            "route" => "/dashboard"
        ],
        [
            "name" => "Income by means of payment",
            "route" => "/dashboard"
        ],
        [
            "name" => "Outcome by categories",
            "route" => "/dashboard"
        ],
        [
            "name" => "Outcome by means of payment",
            "route" => "/dashboard"
        ],
        [
            "name" => "Income by categories",
            "route" => "/income"
        ],
        [
            "name" => "Income by means of payment",
            "route" => "/income"
        ],
        [
            "name" => "Outcome by categories",
            "route" => "/outcome"
        ],
        [
            "name" => "Outcome by means of payment",
            "route" => "/outcome"
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->charts as $chart) {
            Chart::firstOrCreate($chart);
        }
    }
}
