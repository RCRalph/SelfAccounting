<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Currency;
use App\Models\Chart;

use App\Rules\Common\DateBeforeOrEqualField;

class ChartsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function dataByType($io, $type, Currency $currency, $limits)
    {
        // Get type data
        $typeData = $type == "category" ?
            auth()->user()->categories() :
            auth()->user()->meansOfPayment();

        $typeData = $typeData
            ->select("id", "name")
            ->where("currency_id", $currency->id)
            ->where("show_on_charts", true)
            ->where($io . "_". $type, true)
            ->get();

        // Get type IDs to get from io
        $toShow = $typeData->pluck("id")->toArray();

        $ioData = $this->getTypeRelation($io);

        if ($limits["start"]) {
            $ioData = $ioData->whereDate("date", ">=", $limits["start"]);
        }

        if ($limits["end"]) {
            $ioData = $ioData->whereDate("date", "<=", $limits["end"]);
        }

        $ioData = $ioData
            ->select($type . "_id", DB::raw("round(amount * price, 2) AS value"))
            ->where("currency_id", $currency->id)
            ->whereIn($type . "_id", $toShow)
            ->get()
            ->groupBy($type . "_id")
            ->map(fn ($item) => $item->sum("value"));

        $count = $typeData->count();
        $colors = $this->getColors($count);
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
                round($amount, 2)
            );

            array_push(
                $data["datasets"][0]["backgroundColor"],
                $colors[--$count]
            );

            array_push(
                $data["labels"],
                $typeData->firstWhere("id", $typeID)["name"]
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

    private function balanceHistory(Currency $currency, $limits)
    {
        $means = auth()->user()->meansOfPayment()
            ->where("currency_id", $currency->id)
            ->where("show_on_charts", true);

        if ($limits["end"]) {
            $means = $means->whereDate("first_entry_date", "<=", $limits["end"]);
        }

        $means = $means->get();
        $meansToShow = $means->pluck("id")->toArray();

        $income = auth()->user()->income()
            ->select("date", "category_id", "mean_id", DB::raw("round(amount * price, 2) AS value"))
            ->where("currency_id", $currency->id)
            ->whereIn("mean_id", $meansToShow);

        $outcome = auth()->user()->outcome()
            ->select("date", "category_id", "mean_id", DB::raw("round(amount * price, 2) AS value"))
            ->where("currency_id", $currency->id)
            ->whereIn("mean_id", $meansToShow);

        if ($limits["end"]) {
            $income = $income->whereDate("date", "<=", $limits["end"]);
            $outcome = $outcome->whereDate("date", "<=", $limits["end"]);
        }

        $income = $income->get();
        $outcome = $outcome->get();

        if ($limits["start"]) {
            $balanceBefore = $this->getBalance(
                $income->where("date", "<=", $limits["start"]),
                $outcome->where("date", "<=", $limits["start"]),
                $means, [], $meansToShow, []
            );
        }
        else {
            $balanceBefore = $this->getBalance(
                collect([]), collect([]),
                $means, [], $meansToShow, []
            );
        }

        $firstEntries = [];
        foreach ($balanceBefore as $balance) {
            if (isset($balance["mean_id"])) {
                $firstEntries[$balance["mean_id"]] = $balance["balance"];
            }
        }

        if ($limits["start"]) {
            $income = $income->where("date", ">", $limits["start"]);
            $outcome = $outcome->where("date", ">", $limits["start"]);
        }

        $income = $income
            ->groupBy("mean_id")
            ->map(fn ($item) => $item->groupBy("date")
                ->map(fn ($item1) => $item1->count() ?
                    $item1->pluck("value")->reduce(fn ($carry, $item) => $carry + $item) : 0
                )
            );

        $outcome = $outcome
            ->groupBy("mean_id")
            ->map(fn ($item) => $item
                ->groupBy("date")
                ->map(fn ($item1) => $item1->count() ?
                    $item1->pluck("value")->reduce(fn ($carry, $item) => $carry + $item) : 0
                )
            );

        foreach ($means as $mean) {
            if ($income->has($mean->id)) {
                $incomeByMean = $income->get($mean->id);

                if ($incomeByMean->has($mean->first_entry_date)) {
                    $incomeByMean->put(
                        $mean->first_entry_date,
                        $incomeByMean->get($mean->first_entry_date) + $firstEntries[$mean->id]
                    );
                }
                else {
                    $startDate = $mean->first_entry_date;
                    if ($limits["start"] && strtotime($limits["start"]) > strtotime($startDate)) {
                        $startDate = $limits["start"];
                    }

                    $incomeByMean->prepend($firstEntries[$mean->id] * 1, $startDate);
                }

                if (!$incomeByMean->has($limits["end"] ? $limits["end"] : Carbon::today()->format("Y-m-d"))) {
                    $incomeByMean->put($limits["end"] ? $limits["end"] : Carbon::today()->format("Y-m-d"), 0);
                }
            }
            else {
                $startDate = $mean->first_entry_date;
                if ($limits["start"] && strtotime($limits["start"]) > strtotime($startDate)) {
                    $startDate = $limits["start"];
                }

                $retArr = [$startDate => $firstEntries[$mean->id]];
                if (Carbon::today()->gt($startDate)) {
                    $retArr[Carbon::today()->format("Y-m-d")] = 0;
                }

                $income->put($mean->id, collect($retArr));
            }
        }

        $differenceByMeans = $income;
        foreach ($outcome as $meanID => $dates) {
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

    public function index(Chart $chart, Currency $currency) {
        $limits = request()->validate([
            "start" => ["present", "nullable", "date", "after_or_equal:1970-01-01", new DateBeforeOrEqualField("end")],
            "end" => ["present", "nullable", "date", "after_or_equal:1970-01-01"]
        ]);

        $retArr = [];

        switch ($chart->name) {
            case "Balance history":
                $retArr = $this->balanceHistory($currency, $limits);
                break;

            case "Income by categories":
            case "Outcome by categories":
            case "Income by means of payment":
            case "Outcome by means of payment":
                $name = explode(" ", $chart->name);
                $retArr = $this->dataByType(
                    strtolower($name[0]),
                    $name[2] == "categories" ? "category" : "mean",
                    $currency, $limits
                );
                break;


            default:
                abort(500);
        }

        return response()->json(["info" => $chart->only("id", "name"), ...$retArr]);
    }
}
