<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Tutorial;

class TutorialSeeder extends Seeder
{
    private $tutorials = [
        [
            "route" => "/",
            "filename" => "dashboard.md"
        ],
        [
            "route" => "/settings",
            "filename" => "settings.md"
        ],
        [
            "route" => "/income",
            "filename" => "income.md"
        ],
        [
            "route" => "/outcome",
            "filename" => "outcome.md"
        ],
        [
            "route" => "/extensions/store",
            "filename" => "extensions.md"
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->tutorials as $tutorial) {
            Tutorial::updateOrCreate(["route" => $tutorial["route"]], $tutorial);
        }
    }
}