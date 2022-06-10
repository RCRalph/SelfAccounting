<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Currency;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function getColors($numberOfColors)
    {
        $circleShift = rand(1, 360);
        $step = 360 / $numberOfColors;

        $retArr = [];
        for ($i = 0; $i < $numberOfColors; $i++) {
            $h = ($circleShift + $i * $step) % 360;
            $s = rand(80, 100);
            $l = rand(40, 60);
            array_push($retArr, "hsl($h, $s%, $l%)");
        }

        return $retArr;
    }

    private function getBalance($income, $outcome, $means, $categories, $meansToShow, $categoriesToShow)
    {
        $incomeByMeans = $income
            ->whereIn("mean_id", $meansToShow)
            ->groupBy("mean_id")
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

        $outcomeByMeans = $outcome
            ->whereIn("mean_id", $meansToShow)
            ->groupBy("mean_id")
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

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

        foreach ($balanceByCategories->keys() as $categoryID) {
            $category = $categories
                ->where("id", $categoryID)
                ->first();

            foreach ($balanceByCategories[$categoryID]->keys() as $key) {
                $limitedByStart = (
                    $category["start_date"] != null &&
                    strtotime($value["date"]) < strtotime($category["start_date"])
                );

                $limitedByEnd = (
                    $category["end_date"] != null &&
                    strtotime($value["date"]) > strtotime($category["end_date"])
                );

                if ($limitedByStart || $limitedByEnd) {
                    unset($balanceByCategories[$categoryID][$key]);
                }
            }
        }

        $balanceByCategories = $balanceByCategories
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

        $currentBalance = [];
        $foundMeanIDs = [];

        foreach ($balanceByMeans as $meanID => $balance) {
            $foundMean = $means->where("id", $meanID)->first();

            array_push($currentBalance, [
                "mean_id" => $foundMean->id,
                "name" => $foundMean->name,
                "balance" => $balance + $foundMean->first_entry_amount
            ]);

            array_push($foundMeanIDs, $foundMean->id);
        }

        // Add means without any entries
        foreach ($means->whereNotIn("id", $foundMeanIDs) as $mean) {
            array_push($currentBalance, [
                "mean_id" => $mean->id,
                "name" => $mean->name,
                "balance" => $mean->first_entry_amount * 1 // Convert string to number
            ]);
        }

        // Add categories marked as count to summary
        foreach ($balanceByCategories as $categoryID => $balance) {
            $foundCategory = $categories->where("id", $categoryID)->first();

            array_push($currentBalance, [
                "category_id" => $foundCategory->id,
                "name" => $foundCategory->name,
                "balance" => $balance
            ]);
        }

        // Sort by balance DESC
        usort($currentBalance, function ($a, $b) {
            return $b["balance"] - $a["balance"];
        });

        // Round to 2 decimal places
        foreach ($currentBalance as $i => $val) {
            $currentBalance[$i]["balance"] = round($val["balance"], 2);
        }

        return $currentBalance;
    }

    private function getLast30DaysBalance($income, $outcome, $meansToShow, $categoriesToShow)
    {
        $incomeToSum = $income
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->whereIn("mean_id", $meansToShow)
            ->sum("value");

        $outcomeToSum = $outcome
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->whereIn("mean_id", $meansToShow)
            ->sum("value");

        $outcomeCategorySum = $outcome
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->whereIn("category_id", $categoriesToShow)
            ->sum("value");

        return [
            "income" => $incomeToSum,
            "outcome" => $outcomeToSum - $outcomeCategorySum
        ];
    }

    public function index(Currency $currency)
    {
        $categories = auth()->user()->categories
            ->where("currency_id", $currency->id);

        $means = auth()->user()->meansOfPayment
            ->where("currency_id", $currency->id);

        $meansToShow = $means
            ->where("count_to_summary", true)
            ->pluck("id")->toArray();

        $categoriesToShow = $categories
            ->where("count_to_summary", true)
            ->pluck("id")->toArray();

        $income = auth()->user()->income
            ->where("currency_id", $currency->id)
            ->map(function ($item) {
                $item["value"] = round($item["amount"] * $item["price"], 2);

                return collect($item)
                    ->only("value", "category_id", "mean_id", "date")
                    ->toArray();
            });

        $outcome = auth()->user()->outcome
            ->where("currency_id", $currency->id)
            ->map(function ($item) {
                $item["value"] = round($item["amount"] * $item["price"], 2);

                return collect($item)
                    ->only("value", "category_id", "mean_id", "date")
                    ->toArray();
            });

        $currentBalance = $this->getBalance($income, $outcome, $means, $categories, $meansToShow, $categoriesToShow);
        $last30Days = $this->getLast30DaysBalance($income, $outcome, $meansToShow, $categoriesToShow);

        return response()->json(compact("currentBalance", "last30Days"));
    }

    public function balanceHistory(Currency $currency)
    {
        $means = auth()->user()->meansOfPayment
            ->where("currency_id", $currency->id)
            ->where("show_on_charts", true);

        $meansToShow = $means
            ->where("currency_id", $currency->id)
            ->pluck("id")->toArray();

        $income = auth()->user()->income
            ->where("currency_id", $currency->id)
            ->whereIn("mean_id", $meansToShow)
            ->map(function ($item) {
                $item->value = round($item->amount * $item->price, 2);
                return $item->only("date", "value", "category_id", "mean_id");
            });

        $outcome = auth()->user()->outcome
            ->where("currency_id", $currency->id)
            ->whereIn("mean_id", $meansToShow)
            ->map(function ($item) {
                $item->value = round($item->amount * $item->price, 2);
                return $item->only("date", "value", "category_id", "mean_id");
            });

        $balanceBefore = $this->getBalance($income, $outcome, $means, [], $meansToShow, []);

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
                ->map(fn ($item1) => $item1->pluck("value")->reduce(fn ($carry, $item) => $carry + $item))
            );

        $outcomeLast30Days = $outcome
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->groupBy("mean_id")
            ->map(fn ($item) => $item
                ->groupBy("date")
                ->map(fn ($item1) => $item1->pluck("value")->reduce(fn ($carry, $item) => $carry + $item))
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
                            Carbon::today()->subDays(31)->format("Y-m-d")
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
                        Carbon::today()->subDays(31)->format("Y-m-d") => $firstEntries[$mean->id],
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

        usort($datesAndDifferences, fn ($a, $b) => strtotime($a["t"]) > strtotime($b["t"]));

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

        return response()->json(compact("data", "options"));
    }
}
