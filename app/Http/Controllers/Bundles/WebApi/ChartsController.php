<?php

namespace App\Http\Controllers\Bundles\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\CorrectCategoryMeanID;

use App\Currency;
use App\Category;
use App\MeanOfPayment;

class ChartsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "bundle:charts"]);
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

    private function getIOByType($io, $type)
    {
        // Check for valid parameters
        if (!in_array($io, ["income", "outcome"]) ||
            !in_array($type, ["category", "mean"])
        ) {
            abort(500);
        }

        // Get currency data
        $currencies = Currency::all()
            ->map(fn ($item) => $item->only("id", "ISO"));

        $lastCurrency = $this->getLastCurrency();

        // Get type data
        $typeData = [];
        if ($type == "category") {
            $typeData = auth()->user()->categories;
        }
        else {
            $typeData = auth()->user()->meansOfPayment;
        }

        $typeData = $typeData
            ->where("show_on_charts", true)
            ->where($io . "_". $type, true)
            ->map(fn ($item) => $item->only("id", "name", "currency_id"));

        // Get type IDs to get from io
        $toShow = $typeData->pluck("id")->toArray();

        $ioData = [];
        if ($io == "income") {
            $ioData = auth()->user()->income;
        }
        else {
            $ioData = auth()->user()->outcome;
        }

        $ioData = $ioData
            ->whereIn($type . "_id", $toShow)
            ->map(function($item) use ($type) {
                $item->value = $item->price * $item->amount;

                return $item->only("value", "currency_id", ($type . "_id"));
            })
            ->groupBy("currency_id")
            ->map(function($item) use ($type) {
                $item = $item->groupBy($type . "_id");

                return $item->map(
                    fn ($item) => $item->sum("value")
                );
            });

        $count = $typeData->count();
        $colors = $count ? $this->getColors($count) : [];
        $data = [];

        foreach ($ioData as $currencyID => $ioByType) {
            $data[$currencyID] = [
                "datasets" => [
                    [
                        "data" => [],
                        "backgroundColor" => []
                    ]
                ],
                "labels" => []
            ];

            foreach ($ioByType as $typeID => $amount) {
                array_push(
                    $data[$currencyID]["datasets"][0]["data"],
                    $amount
                );

                $count--;
                array_push(
                    $data[$currencyID]["datasets"][0]["backgroundColor"],
                    $colors[$count]
                );

                array_push(
                    $data[$currencyID]["labels"],
                    $typeData->where("id", $typeID)->first()["name"]
                );
            }
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

        return compact("currencies", "lastCurrency", "data", "options");
    }

    public function getPresence()
    {
        $currencies = Currency::all()
            ->map(fn ($item) => $item->only("id", "ISO"));

        $categories = auth()->user()->categories
            ->map(fn ($item) => $item->only(
                "id", "name", "income_category", "outcome_category", "show_on_charts", "currency_id"
            ))
            ->groupBy("currency_id")
            ->toArray();

        $means = auth()->user()->meansOfPayment
            ->map(fn ($item) => $item->only(
                "id", "name", "income_mean", "outcome_mean", "show_on_charts", "currency_id"
            ))
            ->groupBy("currency_id")
            ->toArray();

        foreach ($currencies as $currency) {
            if (!isset($categories[$currency["id"]])) {
                $categories[$currency["id"]] = [];
            }

            if (!isset($means[$currency["id"]])) {
                $means[$currency["id"]] = [];
            }
        }

        $lastCurrency = $this->getLastCurrency();

        return response()->json(compact("currencies", "categories", "means", "lastCurrency"));
    }

    public function updateCategories()
    {
        $data = request()->validate([
            "data.*" => ["required", "integer", "not_in:0", new CorrectCategoryMeanID("category")]
        ]);

        $data = isset($data["data"]) ? $data["data"] : [];

        Category::whereIn("id", $data)->update([
            "show_on_charts" => true
        ]);

        Category::whereNotIn("id", $data)->update([
            "show_on_charts" => false
        ]);

        return response("", 200);
    }

    public function updateMeans()
    {
        $data = request()->validate([
            "data.*" => ["required", "integer", "not_in:0", new CorrectCategoryMeanID("mean")]
        ]);

        $data = isset($data["data"]) ? $data["data"] : [];

        MeanOfPayment::whereIn("id", $data)->update([
            "show_on_charts" => true
        ]);

        MeanOfPayment::whereNotIn("id", $data)->update([
            "show_on_charts" => false
        ]);

        return response("", 200);
    }

    public function incomeByCategories()
    {
        return response()->json($this->getIObyType("income", "category"));
    }

    public function outcomeByCategories()
    {
        return response()->json($this->getIOByType("outcome", "category"));
    }

    public function incomeByMeans()
    {
        return response()->json($this->getIOByType("income", "mean"));
    }

    public function outcomeByMeans()
    {
        return response()->json($this->getIOByType("outcome", "mean"));
    }

    public function balanceMonitor()
    {
        $currencies = Currency::all()
            ->map(fn ($item) => $item->only("id", "ISO"));

        // Pick only means and categories that have to be shown
        $means = auth()->user()->meansOfPayment;
        $toShow = $means->where("show_on_charts", true)->pluck("id")->toArray();

        // Get income and outcome and count the values of entries
        $income = auth()->user()->income
            ->map(function($item) {
                $item->value = $item->amount * $item->price;
                return $item->only("date", "value", "category_id", "mean_id", "currency_id");
            });
        $outcome = auth()->user()->outcome
            ->map(function($item) {
                $item->value = $item->amount * $item->price;
                return $item->only("date", "value", "category_id", "mean_id", "currency_id");
            });

        // Get income and outcome grouped by means and then grouped by dates
        $incomeByMeans = $income
            ->whereIn("mean_id", $toShow)
            ->groupBy("mean_id")
            ->map(fn ($item) => $item
                ->groupBy("date")
                ->map(function($item1) {
                    $sum = 0;

                    foreach ($item1 as $data) {
                        $sum += $data["value"];
                    }

                    return $sum;
                })
            );
        $outcomeByMeans = $outcome
            ->whereIn("mean_id", $toShow)
            ->groupBy("mean_id")
            ->map(fn ($item) => $item
                ->groupBy("date")
                ->map(function($item1) {
                    $sum = 0;

                    foreach ($item1 as $data) {
                        $sum += $data["value"];
                    }

                    return $sum;
                })
            );

        // Add first entry amounts of means to income by means by dates
        foreach ($means as $mean) {
            if ($incomeByMeans->has($mean->id)) {
                $incomeMean = $incomeByMeans->get($mean->id);

                if ($incomeMean->has($mean->first_entry_date)) {
                    $incomeMean->put(
                        $mean->first_entry_date,
                        $incomeMean->get($mean->first_entry_date) + $mean->first_entry_amount
                    );
                }
                else {
                    $incomeMean
                        ->prepend(
                            $mean->first_entry_amount * 1,
                            $mean->first_entry_date
                        );
                }
            }
            else {
                $incomeByMeans->put(
                    $mean->id,
                    collect([
                        $mean->first_entry_date => $mean->first_entry_amount * 1
                    ])
                );
            }
        }

        // Subtract outcome from income to create collection of differences
        $differenceByMeans = $incomeByMeans;
        foreach ($outcomeByMeans as $meanID => $dates) {
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

            $differenceByMeans->get($meanID)->sortKeys();
        }

        // Sum the differences to create collection of balance
        $balanceByMeans = collect();
        foreach ($differenceByMeans as $meanID => $differences) {
            $firstKey = $differences->keys()->first();
            $retArr = collect([
                $firstKey => $differences->first()
            ]);

            foreach ($differences as $date => $difference) {
                if ($date == $firstKey) {
                    continue;
                }

                $retArr->put($date, $retArr->last() + $difference);
            }

            $balanceByMeans->put($meanID, $retArr);
        }

		// Data for chart
		$data = [];
		$lastDate = "1970-01-01";

        // Add proper chart colors
        $count = $balanceByMeans->count();
        $colors = $count ? $this->getColors($count) : [];

        foreach ($balanceByMeans as $meanID => $balance) {
			$mean = $means->where("id", $meanID)->first();

			if (!isset($data[$mean->currency_id])) {
				$data[$mean->currency_id] = [
					"datasets" => []
				];
			}

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

            array_push($data[$mean->currency_id]["datasets"], $retArr);
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

        $lastCurrency = $this->getLastCurrency();

        return response()->json(compact("currencies", "lastCurrency", "data", "options"));
    }
}
