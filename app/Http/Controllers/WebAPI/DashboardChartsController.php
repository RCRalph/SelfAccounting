<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Currency;
use App\Chart;

class DashboardChartsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function dataByType(Currency $currency, $io, $type)
    {
        // Get type data
        $typeData = $type == "category" ?
            auth()->user()->categories :
            auth()->user()->meansOfPayment;

        $typeData = $typeData
            ->where("currency_id", $currency->id)
            ->where("show_on_charts", true)
            ->where($io . "_". $type, true)
            ->map(fn ($item) => $item->only("id", "name"));

        // Get type IDs to get from io
        $toShow = $typeData->pluck("id")->toArray();

        $ioData = $io == "income" ?
            auth()->user()->income :
            auth()->user()->outcome;

        $ioData = $ioData
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->where("currency_id", $currency->id)
            ->whereIn($type . "_id", $toShow)
            ->map(function ($item) use ($type) {
                $item->value = $item->price * $item->amount;

                return $item->only("value", ($type . "_id"));
            })
            ->groupBy($type . "_id")
            ->map(
                fn ($item) => round($item->sum("value"), 2)
            );

        $count = $typeData->count();
        $colors = $count ? $this->getColors($count) : [];
        $data = [
            "datasets" => [
                [
                    "data" => [],
                    "backgroundColor" => []
                ]
            ],
            "labels" => []
        ];

        foreach ($ioData as $typeID => $amount) {
            array_push(
                $data["datasets"][0]["data"],
                $amount
            );

            $count--;
            array_push(
                $data["datasets"][0]["backgroundColor"],
                $colors[$count]
            );

            array_push(
                $data["labels"],
                $typeData->where("id", $typeID)->first()["name"]
            );
        }

        $options = [
            "responsive" => true,
            "maintainAspectRatio" => false,
            "legend" => [
                "display" => true,
                "labels" => [
                    "fontColor" => "#3490dc"
                ]
            ],
            "circumference" => pi(),
            "rotation" => -pi()
        ];

        return compact("data", "options");
    }

    public function balanceHistory(Currency $currency)
    {
        $means = auth()->user()->meansOfPayment()
            ->where("currency_id", $currency->id)
            ->where("show_on_charts", true)
            ->get();

        $meansToShow = $means->pluck("id")->toArray();

        $income = auth()->user()->income()
            ->select("date", "category_id", "mean_id", DB::raw("round(amount * price, 2) AS value"))
            ->where("currency_id", $currency->id)
            ->whereIn("mean_id", $meansToShow)
            ->get();

        $outcome = auth()->user()->outcome()
            ->select("date", "category_id", "mean_id", DB::raw("round(amount * price, 2) AS value"))
            ->where("currency_id", $currency->id)
            ->whereIn("mean_id", $meansToShow)
            ->get();

        $balanceBefore = $this->getBalance(
            $income->where("date", "<", Carbon::today()->subDays(30)),
            $outcome->where("date", "<", Carbon::today()->subDays(30)),
            $means, [], $meansToShow, []);

        $firstEntries = [];
        foreach ($balanceBefore as $balance) {
            if (isset($balance["mean_id"])) {
                $firstEntries[$balance["mean_id"]] = $balance["balance"];
            }
        }

        $incomeLast30Days = $income
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->groupBy("mean_id")
            ->map(fn ($item) => $item
                ->groupBy("date")
                ->map(fn ($item1) => $item1->count() ?
                    $item1->pluck("value")->reduce(fn ($carry, $item) => $carry + $item) : 0
                )
            );

        $outcomeLast30Days = $outcome
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->groupBy("mean_id")
            ->map(fn ($item) => $item
                ->groupBy("date")
                ->map(fn ($item1) => $item1->count() ?
                    $item1->pluck("value")->reduce(fn ($carry, $item) => $carry + $item) : 0
                )
            );

        foreach ($means as $mean) {
            if ($incomeLast30Days->has($mean->id)) {
                $incomeMean = $incomeLast30Days->get($mean->id);

                if ($incomeMean->has($mean->first_entry_date)) {
                    $incomeMean->put(
                        $mean->first_entry_date,
                        $incomeMean->get($mean->first_entry_date) + $firstEntries[$mean->id]
                    );
                }
                else {
                    $incomeMean
                        ->prepend(
                            $firstEntries[$mean->id] * 1,
                            Carbon::today()->subDays(30)->format("Y-m-d")
                        );
                }

                if (!$incomeMean->has(Carbon::today()->format("Y-m-d"))) {
                    $incomeMean->put(Carbon::today()->format("Y-m-d"), 0);
                }
            }
            else {
                $incomeLast30Days->put(
                    $mean->id,
                    collect([
                        Carbon::today()->subDays(30)->format("Y-m-d") => $firstEntries[$mean->id],
                        Carbon::today()->format("Y-m-d") => 0
                    ])
                );
            }
        }

        $differenceByMeans = $incomeLast30Days;
        foreach ($outcomeLast30Days as $meanID => $dates) {
            foreach ($dates as $date => $difference) {
                $differenceMean = $differenceByMeans->get($meanID);

                if ($differenceMean->has($date)) {
                    $differenceMean->put(
                        $date,
                        $differenceMean->get($date) - $difference
                    );
                }
                else {
                    $differenceMean->put(
                        $date,
                        -$difference
                    );
                }
            }

            $differenceByMeans->put(
                $meanID,
                $differenceByMeans->get($meanID)
                    ->sortBy(fn ($val, $key) => strtotime($key))
            );
        }

        $balanceByMeans = collect();
        foreach ($differenceByMeans as $meanID => $differences) {
            $differences = $differences->sortKeys();
            $firstKey = $differences->keys()->first();
            $retArr = collect([
                $firstKey => $differences->first()
            ]);

            foreach ($differences as $date => $difference) {
                if ($date == $firstKey) continue;
                $retArr->put($date, $retArr->last() + $difference);
            }

            $balanceByMeans->put($meanID, $retArr);
        }

		$data = ["datasets" => []];

        $lastDate = null;
        foreach ($balanceByMeans as $meanData) {
            foreach ($meanData->keys() as $date) {
                if ($lastDate === null || strtotime($lastDate) > strtotime($date)) {
                    $lastDate = $date;
                }
            }
        }

        $count = $balanceByMeans->count() + $means
            ->map(fn ($item) => $item->currency_id)
            ->unique()->count();

        $colors = $count ? $this->getColors($count) : [];

        foreach ($balanceByMeans as $meanID => $balance) {
			$mean = $means->where("id", $meanID)->first();

            $count--;
            $retArr = [
                "label" => $mean->name,
                "steppedLine" => true,
				"data" => [],
				"fill" => false,
				"borderWidth" => 5,
				"borderColor" => $colors[$count]
            ];

            foreach ($balance as $date => $amount) {
                array_push($retArr["data"], [
                    "t" => $date,
                    "y" => round($amount, 2)
				]);

				if (strtotime($date) > strtotime($lastDate)) {
					$lastDate = $date;
				}
            }

            array_push($data["datasets"], $retArr);
		}

        $datesAndDifferences = [];
        foreach ($differenceByMeans as $differencesByDate) {
            foreach ($differencesByDate as $date => $value) {
                array_push($datesAndDifferences, [
                    "t" => $date,
                    "y" => $value
                ]);
            }
        }

        usort($datesAndDifferences, fn ($a, $b) => strtotime($a["t"]) - strtotime($b["t"]));

        if (count($datesAndDifferences)) {
            // Sum data by dates
            $sumData = [$datesAndDifferences[0]["t"] => $datesAndDifferences[0]];
            foreach ($datesAndDifferences as $i => $value) {
                if ($i == 0) continue;

                if ($value["t"] == array_key_last($sumData)) {
                    $sumData[$value["t"]]["y"] += $value["y"];
                }
                else {
                    $lastElement = $sumData[array_key_last($sumData)];
                    $sumData[array_key_last($sumData)]["y"] = round($lastElement["y"], 2);
                    $sumData[$value["t"]] = [
                        "t" => $value["t"],
                        "y" => $lastElement["y"] + $value["y"]
                    ];
                }
            }
            $sumData[array_key_last($sumData)]["y"] = round($sumData[array_key_last($sumData)]["y"], 2);
            $sumData = array_values($sumData);

            $count--;
            array_push($data["datasets"], [
                "label" => "Sum",
                "steppedLine" => true,
                "data" => $sumData,
                "fill" => false,
                "borderWidth" => 5,
                "borderColor" => $colors[$count]
            ]);
        }

		// Options for chart
        $options = [
            "responsive" => true,
            "maintainAspectRatio" => false,
            "elements" => [
                "line" => [
                    "tension" => 0
                ]
            ],
            "scales" => [
                "xAxes" => [
                    [
                        "type" => "time",
                        "time" => [
                            "displayFormats" => [
								"day" => "DD MMM",
								"year" => "YYYY-MM-DD"
                            ],
                            "minUnit" => "day"
						],
						"ticks" => [
							"fontColor" => "#3490dc"
						]
                    ]
				],
				"yAxes" => [
					[
						"ticks" => [
							"fontColor" => "#3490dc",
							"beginAtZero" => true
						]
					]
				]
			],
			"legend" => [
				"display" => true,
				"labels" => [
					"fontColor" => "#3490dc"
				]
			]
		];

        return compact("data", "options");
    }

    public function index(Currency $currency, Chart $chart) {
        $retArr = [];

        if ($chart->id == 1) {
            $retArr = $this->balanceHistory($currency);
        }
        else if ($chart->id <= 5) {
            $data = $chart->id <= 3 ? "income" : "outcome";
            $type = $chart->id % 2 ? "mean" : "category";
            $retArr = $this->dataByType($currency, $data, $type);
        }

        return response()->json($retArr);
    }
}
