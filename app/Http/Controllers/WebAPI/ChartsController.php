<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Utils\AccountBalanceHistory;

use App\Models\Currency;
use App\Models\Chart;


class ChartsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    static function getColors($numberOfColors): array
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
            $result[] = "hsl($h, $s%, $l%)";
        }

        return $result;
    }

    private function dataByType($transactionType, $type, Currency $currency, $limits): array
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
            ->map(fn($item) => $item->sum("value"));

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
            $data["datasets"][0]["data"][] = round($amount, 2);
            $data["datasets"][0]["backgroundColor"][] = $colors[--$count];
            $data["labels"][] = $typeData->firstWhere("id", $typeID)["name"];
        }

        return compact("data");
    }

    private function transfersByAccount($type, Currency $currency, $limits): array
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
            ->map(fn($item) => $item->sum($type . "_value"));

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
            $data["datasets"][0]["data"][] = round($value, 2);
            $data["datasets"][0]["backgroundColor"][] = $colors[--$count];
            $data["labels"] = $accounts->firstWhere("id", $accountID)->name;
        }

        return compact("data");
    }

    private function balanceHistory(Currency $currency, $limits): array
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
        $dataSubQuery = auth()->user()->income()
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

        $data = DB::query()->fromSub($dataSubQuery, "data")
            ->select("date", "account_id", DB::raw("sum(value) as value"))
            ->groupBy("date", "account_id")
            ->orderBy("date")
            ->orderBy("account_id");

        $sumData = DB::query()->fromSub($dataSubQuery, "data")
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

        return ["data" => $accountHistory->getChartData()];
    }

    public function show(Chart $chart, Currency $currency): JsonResponse
    {
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

    public function index(): JsonResponse
    {
        $charts = Chart::route("/");

        return response()->json(compact("charts"));
    }
}
