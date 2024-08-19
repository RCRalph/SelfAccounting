<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Currency;
use App\Models\Chart;

use App\Rules\EqualArrayLength;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function getLast30DaysBalance($currency, $accounts, $categories)
    {
        $result = [
            "income" => auth()->user()->income()
                ->whereIn("account_id", $accounts)
                ->whereBetween("date", [Carbon::today()->subDays(29), Carbon::today()])
                ->sum(DB::raw("round(amount * price, 2)")),

            "expenses" => auth()->user()->expenses()
                ->whereBetween("date", [Carbon::today()->subDays(29), Carbon::today()])
                ->whereIn("account_id", $accounts)
                ->sum(DB::raw("round(amount * price, 2)")),

            "transfersIn" => auth()->user()->transfers()
                ->whereIn("target_account_id", $accounts)
                ->whereBetween("date", [Carbon::today()->subDays(29), Carbon::today()])
                ->sum("target_value"),

            "transfersOut" => auth()->user()->transfers()
                ->whereIn("source_account_id", $accounts)
                ->whereBetween("date", [Carbon::today()->subDays(29), Carbon::today()])
                ->sum("source_value")
        ];

        foreach (array_keys($result) as $key) {
            $result[$key] *= 1;
        }

        return $result;
    }

    public function getRecentTransactions(Currency $currency, $filteredDates = null, $searchTerm = null)
    {
        $income = auth()->user()->income()
            ->where("currency_id", $currency->id)
            ->select("id", "date", "title", "amount", "price", DB::raw("round(amount * price, 2) AS value"), "category_id", "account_id", DB::raw("1 AS type"));

        $expenses = auth()->user()->expenses()
            ->where("currency_id", $currency->id)
            ->select("id", "date", "title", "amount", "price", DB::raw("round(amount * price, 2) AS value"), "category_id", "account_id", DB::raw("-1 AS type"));

        $fields = ["date", "title", "amount", "price", "value"];

        $data = request()->validate([
            "title" => ["nullable", "string", "min:1", "max:64"],
            "dates" => ["nullable", "array"],
            "dates.*" => ["required", "date", "distinct"],
            "categories" => ["nullable", "array"],
            "categories.*" => ["required", "integer", "exists:categories,id"],
            "accounts" => ["nullable", "array"],
            "accounts.*" => ["required", "integer", "exists:accounts,id"],
            "orderFields" => ["nullable", "array", new EqualArrayLength("orderDirections")],
            "orderFields.*" => ["required", "string", "in:" . implode(",", $fields), "distinct"],
            "orderDirections" => ["nullable", "array", new EqualArrayLength("orderFields")],
            "orderDirections.*" => ["nullable", "string", "in:asc,desc"]
        ]);

        if (isset($data["title"])) {
            $income->where("title", "ilike", "%" . $data["title"] . "%");
            $expenses->where("title", "ilike", "%" . $data["title"] . "%");
        }

        if (isset($data["dates"])) {
            $income->whereIn("date", $data["dates"]);
            $expenses->whereIn("date", $data["dates"]);
        }

        if (isset($data["categories"])) {
            $income->whereIn("category_id", $data["categories"]);
            $expenses->whereIn("category_id", $data["categories"]);
        }

        if (isset($data["accounts"])) {
            $income->whereIn("account_id", $data["accounts"]);
            $expenses->whereIn("account_id", $data["accounts"]);
        }

        $items = $income->union($expenses);

        if (isset($data["orderFields"]) && isset($data["orderDirections"])) {
            foreach ($data["orderFields"] as $i => $item) {
                $fields = array_diff($fields, [$item]);
                $items = $items->orderBy($item, $data["orderDirections"][$i] ?? "asc");
            }
        }

        foreach ($fields as $field) {
            $items = $items->orderBy($field, $field == "date" ? "desc" : "asc");
        }

        $items = $this->addNamesToPaginatedTransactionsItems($items->paginate(20), $currency);

        return response()->json(compact("items"));
    }

    public function index(Currency $currency)
    {
        $accounts = auth()->user()->accounts()
            ->select("id", "name", "icon", "start_date", "start_balance")
            ->where("currency_id", $currency->id)
            ->where("count_to_summary", true)
            ->orderBy("name")
            ->get();

        $categories = auth()->user()->categories()
            ->select("id", "name", "icon", "count_to_summary", "start_date", "end_date")
            ->where("currency_id", $currency->id)
            ->orderBy("name")
            ->get();

        $currentBalance = auth()->user()->balance($accounts, $categories);
        $last30Days = $this->getLast30DaysBalance($currency, $accounts->pluck("id"), $categories->pluck("id"));

        $charts = Chart::route("/dashboard");

        return response()->json(compact("categories", "accounts", "currentBalance", "last30Days", "charts"));
    }
}
