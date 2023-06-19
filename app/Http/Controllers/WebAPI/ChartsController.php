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

    private function dataByType($transactionType, $type, Currency $currency, $limits)
    {
        // Get type data
        $typeData = $type == "category" ?
            auth()->user()->categories() :
            auth()->user()->accounts();

        $typeData = $typeData
            ->select("id", "name")
            ->where("currency_id", $currency->id)
            ->where("show_on_charts", true)
            ->where("used_in_" . $transactionType, true)
            ->get();

        // Get type IDs to get from income and expenses
        $toShow = $typeData->pluck("id")->toArray();

        $transactionsData = $this->getTypeRelation($transactionType);

        if ($limits["start"]) {
            $transactionsData = $transactionsData->whereDate("date", ">=", $limits["start"]);
        }

        if ($limits["end"]) {
            $transactionsData = $transactionsData->whereDate("date", "<=", $limits["end"]);
        }

        $transactionsData = $transactionsData
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

        foreach ($transactionsData as $typeID => $amount) {
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

        $expenses = auth()->user()->expenses()
            ->select("date", "category_id", "account_id", DB::raw("round(amount * price, 2) AS value"))
            ->where("currency_id", $currency->id)
            ->whereIn("account_id", $accountsToShow);

        $transfers = auth()->user()->transfers()
            ->select("date", "source_account_id", "source_value", "target_account_id", "target_value")
            ->where(function ($query) use ($accountsToShow) {
                $query->whereIn("source_account_id", $accountsToShow)
                    ->orWhereIn("target_account_id", $accountsToShow);
            });

        if ($limits["start"]) {
            $income = $income->where("date", ">", $limits["start"]);
            $expenses = $expenses->where("date", ">", $limits["start"]);
            $transfers = $transfers->where("date", ">", $limits["start"]);
        }

        if ($limits["end"]) {
            $income = $income->where("date", "<=", $limits["end"]);
            $expenses = $expenses->where("date", "<=", $limits["end"]);
            $transfers = $transfers->where("date", "<=", $limits["end"]);
        }

        $income = $income->get();
        $expenses = $expenses->get();
        $transfers = $transfers->get();

        if ($limits["start"]) {
            $balanceBefore = auth()->user()->balance($accounts, [], $limits["start"]);
        } else {
            $balanceBefore = [];
            foreach ($accounts as $account) {
                array_push($balanceBefore, [
                    "account_id" => $account->id,
                    "balance" => $account->start_balance * 1
                ]);
            }
        }

        $firstEntries = [];
        foreach ($balanceBefore as $balance) {
            if (isset($balance["account_id"])) {
                $firstEntries[$balance["account_id"]] = $balance["balance"];
            } else {
                $firstEntries[$balance["account_id"]] = auth()->user()->accounts->find($balance["account_id"])->start_balance;
            }
        }

        $income = $income
            ->groupBy("account_id")
            ->map(fn ($item) => $item->groupBy("date")
                ->map(fn ($item1) => $item1->count() ?
                    $item1->pluck("value")->reduce(fn ($carry, $item) => $carry + $item) : 0
                )
            );

        $expenses = $expenses
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
                } else {
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
        foreach ($expenses as $accountID => $dates) {
            foreach ($dates as $date => $difference) {
                $differenceAccount = $differenceByAccounts->get($accountID);

                if ($differenceAccount->has($date)) {
                    $differenceAccount->put(
                        $date,
                        $differenceAccount->get($date) - $difference
                    );
                } else {
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
                } else {
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
                } else {
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

        usort($data["datasets"], fn ($a, $b) => strcmp($a["label"], $b["label"]));

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
                } else {
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
            "start" => ["present", "nullable", "date", "after_or_equal:1970-01-01"],
            "end" => ["present", "nullable", "date", "after_or_equal:1970-01-01"]
        ]);

        if ($limits["start"] && $limits["end"] && strtotime($limits["start"]) > strtotime($limits["end"])) {
            abort(422, "Start date is after end date");
        }

        $result = [];

        switch ($chart->name) {
            case "Balance history":
                $result = $this->balanceHistory($currency, $limits);
                break;

            case "Income by categories":
            case "Expenses by categories":
            case "Income by accounts":
            case "Expenses by accounts":
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
