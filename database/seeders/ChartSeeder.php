<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Chart;

class ChartSeeder extends Seeder
{
    private $charts = [
        [
            "id" => 1,
            "name" => "Balance history"
        ],
        [
            "id" => 2,
            "name" => "Income by categories"
        ],
        [
            "id" => 3,
            "name" => "Income by means of payment"
        ],
        [
            "id" => 4,
            "name" => "Outcome by categories"
        ],
        [
            "id" => 5,
            "name" => "Outcome by means of payment"
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->charts as $chart) {
            Chart::updateOrCreate([ "id" => $chart["id"] ], $chart);
        }
    }
}
