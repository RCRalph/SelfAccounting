<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;

use App\Models\Currency;
use App\Models\Chart;


class AccountBalanceHistory
{
    private $data, $lastDate, $accounts, $colors, $datasetsConfig;

    public function __construct(Collection $accounts, string|null $lastDate)
    {
        $this->data = [];
        $this->lastDate = null;
        $this->accounts = [];
        $this->lastDate = $lastDate;

        $this->datasetsConfig = [
            "steppedLine" => true,
            "fill" => false,
            "borderWidth" => 5,
        ];

        $this->colors = ChartsController::getColors($accounts->count() + 1);
        foreach ($accounts as $i => $account) {
            $this->accounts[$account->id] = [
                "name" => $account->name,
                "color" => $this->colors[$i]
            ];
        }
    }

    private function addEntry(int $account, string $date, float $value)
    {
        if (!array_key_exists($account, $this->data)) {
            $this->data[$account] = [];
        }

        array_push($this->data[$account], [
            "t" => $date,
            "y" => round($value + ($this->data[$account] ? end($this->data[$account])["y"] : 0), 2)
        ]);

        if (is_null($this->lastDate) || strtotime($this->lastDate) < strtotime($date)) {
            $this->lastDate = $date;
        }
    }

    public function addStartBalance(string $startDate, array $startBalance)
    {
        foreach ($startBalance as $balance) {
            $this->addEntry($balance["account_id"], $startDate, $balance["balance"]);
            $this->addEntry(0, $startDate, $balance["balance"]);
        }
    }

    public function addEntries(Collection $data)
    {
        foreach ($data as $entry) {
            $this->addEntry($entry->account_id, $entry->date, $entry->value);
        }
    }

    public function addSum(Collection $data)
    {
        $this->accounts[0] = [
            "name" => "Sum",
            "color" => end($this->colors)
        ];

        foreach ($data as $entry) {
            $this->addEntry(0, $entry->date, $entry->value);
        }
    }

    public function getChartData()
    {
        $result = [ "datasets" => [] ];

        foreach ($this->accounts as $id => $data) {
            if ($this->data[$id] && end($this->data[$id])["t"] != $this->lastDate) {
                array_push($this->data[$id], [
                    "t" => $this->lastDate,
                    "y" => end($this->data[$id])["y"]
                ]);
            }

            array_push($result["datasets"], [
                ...$this->datasetsConfig,
                "label" => $data["name"],
                "data" => $this->data[$id],
                "borderColor" => $data["color"]
            ]);
        }

        return $result;
    }
}

class ChartsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    static function getColors($numberOfColors)
    {
        if (!$numberOfColors) {
            return [];
        }

        $circleShift = rand(1, 360);
        $step = round(360 / $numberOfColors);

        $result = [];
        for ($i = 0; $i < $numberOfColors; $i++) {
            $h = ($circleShift + $i * $step) % 360;
            $s = rand(80, 100);
            $l = rand(40, 60);
            array_push($result, "hsl($h, $s%, $l%)");
        }

        return $result;
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

        $theme = [
            "fontColor" => ["rgba(0, 0, 0, 0.87)", "rgba(255, 255, 255, 0.87)"]
        ];

        $options = [
            "responsive" => true,
            "maintainAspectRatio" => false,
            "legend" => [
                "display" => true,
                "labels" => [
                    "fontColor" => null
                ]
            ],
            "circumference" => pi(),
            "rotation" => -pi()
        ];

        return compact("data", "options", "theme");
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
                    "backgroundColor" => [],
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

        $theme = [
            "fontColor" => ["rgba(0, 0, 0, 0.87)", "rgba(255, 255, 255, 0.87)"]
        ];

        $options = [
            "responsive" => true,
            "maintainAspectRatio" => false,
            "legend" => [
                "display" => true,
                "labels" => [
                    "fontColor" => null
                ]
            ],
            "circumference" => pi(),
            "rotation" => -pi()
        ];

        return compact("data", "options", "theme");
    }

    private function balanceHistory(Currency $currency, $limits)
    {
        $accounts = auth()->user()->accounts()
            ->select("id", "icon", "name", "start_date", "start_balance")
            ->where("currency_id", $currency->id)
            ->where("show_on_charts", true)
            ->orderBy("name");

        if ($limits["end"]) {
            $accounts = $accounts->whereDate("start_date", "<=", $limits["end"]);
        }

        $accounts = $accounts->get();
        $accountIDs = array_column($accounts->toArray(), "id");

        /*
            Gather all data from income, expenses and transfers with given accounts and dates to a table of values,
            then sum these values with given date and account and order them by firstly account and then date.
            This list also includes first entries for accounts, which simplifies the process of adding these values
            later in the process and avoids issues with start date being after the start limit of the chart.
        */
        $dataSubquery = auth()->user()->income()
            ->select("account_id", "date", DB::raw("sum(round(amount * price, 2)) AS value"))
            ->whereIn("account_id", $accountIDs)
            ->groupBy("account_id", "date")
            ->union(
                auth()->user()->expenses()
                    ->select("account_id", "date", DB::raw("-sum(round(amount * price, 2)) AS value"))
                    ->whereIn("account_id", $accountIDs)
                    ->groupBy("account_id", "date")
            )->union(
                auth()->user()->transfers()
                    ->select(DB::raw("source_account_id AS account_id"), "date", DB::raw("-sum(source_value) AS value"))
                    ->whereIn("source_account_id", $accountIDs)
                    ->groupBy("account_id", "date")
            )->union(
                auth()->user()->transfers()
                    ->select(DB::raw("target_account_id AS account_id"), "date", DB::raw("sum(target_value) AS value"))
                    ->whereIn("target_account_id", $accountIDs)
                    ->groupBy("account_id", "date")
            )->union(
                auth()->user()->accounts()
                    ->select(DB::raw("id AS account_id"), DB::raw("start_date AS date"), DB::raw("start_balance AS value"))
                    ->whereIn("id", $accountIDs)
            );

        $data = DB::query()->fromSub($dataSubquery, "data")
            ->select("date", "account_id", DB::raw("sum(value) as value"))
            ->groupBy("date", "account_id")
            ->orderBy("date")
            ->orderBy("account_id");

        $sumData = DB::query()->fromSub($dataSubquery, "data")
            ->select("date", DB::raw("sum(value) as value"))
            ->groupBy("date")
            ->orderBy("date");

        if ($limits["start"]) {
            $data = $data->where("date", ">", $limits["start"]); // We can skip the equality because we get that information later from account balance
            $sumData = $sumData->where("date", ">", $limits["start"]);
        }

        if ($limits["end"]) {
            $data = $data->where("date", "<=", $limits["end"]);
            $sumData = $sumData->where("date", "<=", $limits["end"]);
        }

        $accountHistory = new AccountBalanceHistory($accounts, $limits["end"]);
        if ($limits["start"]) {
            $accountHistory->addStartBalance(
                $limits["start"],
                auth()->user()->balance($accounts, [], $limits["start"])
            );
        }

        $accountHistory->addEntries($data->get());
        $accountHistory->addSum($sumData->get());

        return [
            "data" => $accountHistory->getChartData($limits["end"]),
            "theme" => [
                "fontColor" => ["rgba(0, 0, 0, 0.87)", "rgba(255, 255, 255, 0.87)"],
                "color" => ["rgba(0, 0, 0, 0.1)", "rgba(255, 255, 255, 0.1)"]
            ],
            "options" => [
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
                                "fontColor" => null
                            ],
                            "gridLines" => [
                                "color" => null,
                            ]
                        ]
                    ],
                    "yAxes" => [
                        [
                            "ticks" => [
                                "fontColor" => null,
                                "beginAtZero" => true
                            ],
                            "gridLines" => [
                                "color" => "#fff"
                            ]
                        ]
                    ]
                ],
                "legend" => [
                    "display" => true,
                    "labels" => [
                        "fontColor" => null
                    ]
                ]
            ]
        ];
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
