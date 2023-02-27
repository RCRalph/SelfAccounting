<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Currency;
use App\Models\Chart;

use App\Rules\Common\SameLengthAs;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function getLast30DaysBalance($income, $expences, $transfers, $accountIDs, $accountsToShow, $categoriesToShow)
    {
        $incomeToSum = $income
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->whereIn("account_id", $accountsToShow)
            ->sum("value");

        $expencesToSum = $expences
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->whereIn("account_id", $accountsToShow)
            ->sum("value");

        $expencesCategorySum = $expences
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->whereIn("category_id", $categoriesToShow)
            ->sum("value");

        $transfersIn = $transfers
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->whereIn("target_account_id", $accountIDs)
            ->sum("target_value");

        $transfersOut = $transfers
            ->whereBetween("date", [Carbon::today()->subDays(30), Carbon::today()])
            ->whereIn("source_account_id", $accountIDs)
            ->sum("source_value");

        return [
            "income" => $incomeToSum,
            "expences" => $expencesToSum - $expencesCategorySum,
            "transfersIn" => $transfersIn,
            "transfersOut" => $transfersOut
        ];
    }

    public function getRecentTransactions(Currency $currency, $filteredDates = null, $searchTerm = null)
    {
        $income = auth()->user()->income()
            ->where("currency_id", $currency->id)
            ->select("id", "date", "title", "amount", "price", DB::raw("round(amount * price, 2) AS value"), "category_id", "account_id", DB::raw("1 AS type"));

        $expences = auth()->user()->expences()
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
            "orderFields" => ["nullable", "array", new SameLengthAs("orderDirections")],
            "orderFields.*" => ["required", "string", "in:" . implode(",", $fields), "distinct"],
            "orderDirections" => ["nullable", "array", new SameLengthAs("orderFields")],
            "orderDirections.*" => ["nullable", "string", "in:asc,desc"]
        ]);

        if (isset($data["title"])) {
            $income->where("title", "ilike", "%" . $data["title"] . "%");
            $expences->where("title", "ilike", "%" . $data["title"] . "%");
        }

        if (isset($data["dates"])) {
            $income->whereIn("date", $data["dates"]);
            $expences->whereIn("date", $data["dates"]);
        }

        if (isset($data["categories"])) {
            $income->whereIn("category_id", $data["categories"]);
            $expences->whereIn("category_id", $data["categories"]);
        }

        if (isset($data["accounts"])) {
            $income->whereIn("account_id", $data["accounts"]);
            $expences->whereIn("account_id", $data["accounts"]);
        }

        $items = $income->union($expences);

        if (isset($data["orderFields"]) && isset($data["orderDirections"])) {
            foreach ($data["orderFields"] as $i => $item) {
                $fields = array_diff($fields, [$item]);
                $items = $items->orderBy($item, $data["orderDirections"][$i] ?? "asc");
            }
        }

        foreach ($fields as $field) {
            $items = $items->orderBy($field, $field == "date" ? "desc" : "asc");
        }

        $items = $this->addNamesToPaginatedIncomeOrExpencesItems($items->paginate(20), $currency);

        return response()->json(compact("items"));
    }

    public function index(Currency $currency)
    {
        $categories = auth()->user()->categories()
            ->select("id")
            ->where("currency_id", $currency->id)
            ->orderBy("name");

        $accounts = auth()->user()->accounts()
            ->select("id")
            ->where("currency_id", $currency->id);

        $categoriesToShow = $categories
            ->where("count_to_summary", true)
            ->pluck("id")->toArray();

        $accountsToShow = $accounts
            ->where("count_to_summary", true)
            ->pluck("id")->toArray();

        $accountIDs = $accounts->pluck("id")->toArray();

        $accounts = $accounts->addSelect("icon", "name", "start_date", "start_balance")->get();
        $categories = $categories->addSelect("icon", "name", "count_to_summary", "start_date", "end_date")->get();

        $income = auth()->user()->income()
            ->select("date", "category_id", "account_id", DB::raw("round(amount * price, 2) AS value"))
            ->where("currency_id", $currency->id)
            ->get();

        $expences = auth()->user()->expences()
            ->select("date", "category_id", "account_id", DB::raw("round(amount * price, 2) AS value"))
            ->where("currency_id", $currency->id)
            ->get();

        $transfers = auth()->user()->transfers()
            ->select("date", "source_account_id", "source_value", "target_account_id", "target_value")
            ->where(function ($query) use ($accountIDs) {
                $query->whereIn("source_account_id", $accountIDs)
                    ->orWhereIn("target_account_id", $accountIDs);
            })
            ->get();

        $currentBalance = $this->getBalance($income, $expences, $transfers, $accounts, $categories, $accountsToShow, $categoriesToShow);
        $last30Days = $this->getLast30DaysBalance($income, $expences, $transfers, $accountIDs, $accountsToShow, $categoriesToShow);

        $categories = auth()->user()->categories()
            ->select("id", "name")
            ->where("currency_id", $currency->id)
            ->orderBy("name")
            ->get();

        $accounts = auth()->user()->accounts()
            ->select("id", "name")
            ->where("currency_id", $currency->id)
            ->orderBy("name")
            ->get();

        $charts = $this->getCharts("/dashboard");

        return response()->json(compact("categories", "accounts", "currentBalance", "last30Days", "charts"));
    }
}
