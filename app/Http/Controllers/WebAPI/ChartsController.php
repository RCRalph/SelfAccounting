<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Currency;
use App\Models\Chart;

class ChartsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function dataByType($incomeExpences, $type, Currency $currency, $limits)
    {
        // Get type data
        $typeData = $type == "category" ?
            auth()->user()->categories() :
            auth()->user()->accounts();

        $typeData = $typeData
            ->select("id", "name")
            ->where("currency_id", $currency->id)
            ->where("show_on_charts", true)
            ->where("used_in_" . $incomeExpences, true)
            ->get();

        // Get type IDs to get from income and expences
        $toShow = $typeData->pluck("id")->toArray();

        $incomeExpencesData = $this->getTypeRelation($incomeExpences);

        if ($limits["start"]) {
            $incomeExpencesData = $incomeExpencesData->whereDate("date", ">=", $limits["start"]);
        }

        if ($limits["end"]) {
            $incomeExpencesData = $incomeExpencesData->whereDate("date", "<=", $limits["end"]);
        }

        $incomeExpencesData = $incomeExpencesData
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

        foreach ($incomeExpencesData as $typeID => $amount) {
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

    private function transfersByAccount($type, Currency $currency, $limits)
    {
        if (!in_array($type, ["source", "target"])) {
            abort(500, "Invalid type");
        }

        $accounts = auth()->user()->accounts()
            ->select("id", "name")
            ->where("currency_id", $currency->id)
            ->get();

        $items = auth()->user()->transfers()
            ->select($type . "_account_id", $type . "_value")
            ->whereIn($type . "_account_id", $accounts->pluck("id"));

        if ($limits["start"]) {
            $items = $items->whereDate("date", ">=", $limits["start"]);
        }

        if ($limits["end"]) {
            $items = $items->whereDate("date", "<=", $limits["end"]);
        }

        $items = $items->get()
            ->groupBy($type . "_account_id")
            ->map(fn ($item) => $item->sum($type . "_value"));

        $count = $accounts->count();
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

        foreach ($items as $accountID => $value) {
            array_push(
                $data["datasets"][0]["data"],
                round($value, 2)
            );

            array_push(
                $data["datasets"][0]["backgroundColor"],
                $colors[--$count]
            );

            array_push(
                $data["labels"],
                $accounts->firstWhere("id", $accountID)->name
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
        $accounts = auth()->user()->accounts()
            ->where("currency_id", $currency->id)
            ->where("show_on_charts", true);

        if ($limits["end"]) {
            $accounts = $accounts->whereDate("start_date", "<=", $limits["end"]);
        }

        $accounts = $accounts->get();
        $accountsToShow = $accounts->pluck("id")->toArray();

        $income = auth()->user()->income()
            ->select("date", "category_id", "account_id", DB::raw("round(amount * price, 2) AS value"))
            ->where("currency_id", $currency->id)
            ->whereIn("account_id", $accountsToShow);

        $expences = auth()->user()->expences()
            ->select("date", "category_id", "account_id", DB::raw("round(amount * price, 2) AS value"))
            ->where("currency_id", $currency->id)
            ->whereIn("account_id", $accountsToShow);

        $transfers = auth()->user()->transfers()
            ->select("date", "source_account_id", "source_value", "target_account_id", "target_value")
            ->where(function ($query) use ($accountsToShow) {
                $query->whereIn("source_account_id", $accountsToShow)
                    ->orWhereIn("target_account_id", $accountsToShow);
            })
            ->get();

        if ($limits["end"]) {
            $income = $income->where("date", "<=", $limits["end"]);
            $expences = $expences->where("date", "<=", $limits["end"]);
            $transfers = $transfers->where("date", "<=", $limits["end"]);
        }

        $income = $income->get();
        $expences = $expences->get();

        if ($limits["start"]) {
            $balanceBefore = $this->getBalance(
                $income->where("date", "<=", $limits["start"]),
                $expences->where("date", "<=", $limits["start"]),
                $transfers->where("date", "<=", $limits["start"]),
                $accounts, [], $accountsToShow, []
            );
        }
        else {
            $balanceBefore = $this->getBalance(
                collect([]), collect([]), collect([]),
                $accounts, [], $accountsToShow, []
            );
        }

        $firstEntries = [];
        foreach ($balanceBefore as $balance) {
            if (isset($balance["account_id"])) {
                $firstEntries[$balance["account_id"]] = $balance["balance"];
            }
        }

        if ($limits["start"]) {
            $income = $income->where("date", ">", $limits["start"]);
            $expences = $expences->where("date", ">", $limits["start"]);
            $transfers = $transfers->where("date", ">", $limits["start"]);
        }

        $income = $income
            ->groupBy("account_id")
            ->map(fn ($item) => $item->groupBy("date")
                ->map(fn ($item1) => $item1->count() ?
                    $item1->pluck("value")->reduce(fn ($carry, $item) => $carry + $item) : 0
                )
            );

        $expences = $expences
            ->groupBy("account_id")
            ->map(fn ($item) => $item
                ->groupBy("date")
                ->map(fn ($item1) => $item1->count() ?
                    $item1->pluck("value")->reduce(fn ($carry, $item) => $carry + $item) : 0
                )
            );

        foreach ($accounts as $account) {
            if ($income->has($account->id)) {
                $incomeByAccount = $income->get($account->id);

                if ($incomeByAccount->has($account->start_date)) {
                    $incomeByAccount->put(
                        $account->start_date,
                        $incomeByAccount->get($account->start_date) + $firstEntries[$account->id]
                    );
                }
                else {
                    $startDate = $account->start_date;
                    if ($limits["start"] && strtotime($limits["start"]) > strtotime($startDate)) {
                        $startDate = $limits["start"];
                    }

                    $incomeByAccount->prepend($firstEntries[$account->id] * 1, $startDate);
                }

                if ($account->count_to_summary && !$incomeByAccount->has($limits["end"] ? $limits["end"] : Carbon::today()->format("Y-m-d"))) {
                    $incomeByAccount->put($limits["end"] ? $limits["end"] : Carbon::today()->format("Y-m-d"), 0);
                }
            }
            else {
                $startDate = $account->start_date;
                if ($limits["start"] && strtotime($limits["start"]) > strtotime($startDate)) {
                    $startDate = $limits["start"];
                }

                $retArr = [$startDate => $firstEntries[$account->id]];
                if ($limits["end"] && strtotime($limits["end"]) >= strtotime($startDate)) {
                    $retArr[$limits["end"]] = 0;
                } else if (Carbon::today()->gt($startDate)) {
                    $retArr[Carbon::today()->format("Y-m-d")] = 0;
                }

                $income->put($account->id, collect($retArr));
            }
        }

        $differenceByAccounts = $income;
        foreach ($expences as $accountID => $dates) {
            foreach ($dates as $date => $difference) {
                $differenceAccount = $differenceByAccounts->get($accountID);

                if ($differenceAccount->has($date)) {
                    $differenceAccount->put(
                        $date,
                        $differenceAccount->get($date) - $difference
                    );
                }
                else {
                    $differenceAccount->put(
                        $date,
                        -$difference
                    );
                }
            }

            $differenceByAccounts->put(
                $accountID,
                $differenceByAccounts->get($accountID)
                    ->sortBy(fn ($val, $key) => strtotime($key))
            );
        }

        // Add transfers to differences
        $transfersIn = $transfers
            ->whereIn("target_account_id", $accountsToShow)
            ->groupBy("target_account_id")
            ->map(fn ($item) => $item
                ->groupBy("date")
                ->map(fn ($item1) => $item1->count() ?
                    $item1->pluck("target_value")->reduce(fn ($carry, $item) => $carry + $item) : 0
                )
            );

        foreach ($transfersIn as $accountID => $dates) {
            foreach ($dates as $date => $difference) {
                $differenceAccount = $differenceByAccounts->get($accountID);

                if ($differenceAccount->has($date)) {
                    $differenceAccount->put(
                        $date,
                        $differenceAccount->get($date) + $difference
                    );
                }
                else {
                    $differenceAccount->put(
                        $date,
                        $difference
                    );
                }
            }

            $differenceByAccounts->put(
                $accountID,
                $differenceByAccounts->get($accountID)
                    ->sortBy(fn ($val, $key) => strtotime($key))
            );
        }

        $transfersOut = $transfers
            ->whereIn("source_account_id", $accountsToShow)
            ->groupBy("source_account_id")
            ->map(fn ($item) => $item
                ->groupBy("date")
                ->map(fn ($item1) => $item1->count() ?
                    $item1->pluck("source_value")->reduce(fn ($carry, $item) => $carry + $item) : 0
                )
            );

        foreach ($transfersOut as $accountID => $dates) {
            foreach ($dates as $date => $difference) {
                $differenceAccount = $differenceByAccounts->get($accountID);

                if ($differenceAccount->has($date)) {
                    $differenceAccount->put(
                        $date,
                        $differenceAccount->get($date) - $difference
                    );
                }
                else {
                    $differenceAccount->put(
                        $date,
                        -$difference
                    );
                }
            }

            $differenceByAccounts->put(
                $accountID,
                $differenceByAccounts->get($accountID)
                    ->sortBy(fn ($val, $key) => strtotime($key))
            );
        }

        $balanceByAccounts = collect();
        foreach ($differenceByAccounts as $accountID => $differences) {
            $differences = $differences->sortKeys();
            $firstKey = $differences->keys()->first();
            $retArr = collect([
                $firstKey => $differences->first()
            ]);

            foreach ($differences as $date => $difference) {
                if ($date == $firstKey) continue;
                $retArr->put($date, $retArr->last() + $difference);
            }

            $balanceByAccounts->put($accountID, $retArr);
        }

		$data = ["datasets" => []];

        $lastDate = null;
        foreach ($balanceByAccounts as $accountData) {
            foreach ($accountData->keys() as $date) {
                if ($lastDate === null || strtotime($lastDate) > strtotime($date)) {
                    $lastDate = $date;
                }
            }
        }

        $count = $balanceByAccounts->count() + $accounts
            ->map(fn ($item) => $item->currency_id)
            ->unique()->count();

        $colors = $this->getColors($count);

        foreach ($balanceByAccounts as $accountID => $balance) {
			$account = $accounts->firstWhere("id", $accountID);

            $count--;
            $retArr = [
                "label" => $account->name,
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
        foreach ($differenceByAccounts as $differencesByDate) {
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
            "start" => ["present", "nullable", "date", "after_or_equal:1970-01-01", "before_or_equal:end"],
            "end" => ["present", "nullable", "date", "after_or_equal:1970-01-01", "after_or_equal:start"]
        ]);

        $result = [];

        switch ($chart->name) {
            case "Balance history":
                $result = $this->balanceHistory($currency, $limits);
                break;

            case "Income by categories":
            case "Expences by categories":
            case "Income by accounts":
            case "Expences by accounts":
                $name = explode(" ", $chart->name);
                $result = $this->dataByType(
                    strtolower($name[0]),
                    $name[2] == "categories" ? "category" : "account",
                    $currency, $limits
                );
                break;

            case "Transfers by source accounts":
            case "Transfers by target accounts":
                $name = explode(" ", $chart->name);
                $result = $this->transfersByAccount($name[2], $currency, $limits);
                break;

            default:
                abort(500, "Invalid chart name");
        }

        return response()->json(["info" => $chart->only("id", "name", "type"), ...$result]);
    }
}
