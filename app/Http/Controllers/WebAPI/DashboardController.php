<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Currency;
use App\Chart;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
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

    public function getRecentTransactions(Currency $currency, $filteredDates = null, $searchTerm = null)
    {
        $income = auth()->user()->income()
            ->where("currency_id", $currency->id)
            ->select("id", "date", "title", "amount", "price", DB::raw("round(amount * price, 2) AS value"), "category_id", "mean_id", DB::raw("1 AS type"));
        $outcome = auth()->user()->outcome()
            ->where("currency_id", $currency->id)
            ->select("id", "date", "title", "amount", "price", DB::raw("round(amount * price, 2) AS value"), "category_id", "mean_id", DB::raw("-1 AS type"));

        $items = $income
            ->union($outcome)
            ->orderBy("date", "desc")
            ->orderBy("title")
            ->orderBy("amount")
            ->orderBy("price")
            ->orderBy("category_id")
            ->orderBy("mean_id")
            ->paginate(20);

        $items = $this->addNamesToPaginatedIOItems($items, $currency);

        return response()->json(compact("items"));
    }

    public function index(Currency $currency)
    {
        $categories = auth()->user()->categories()
            ->select("id")
            ->where("currency_id", $currency->id);

        $means = auth()->user()->meansOfPayment()
            ->select("id")
            ->where("currency_id", $currency->id);

        $categoriesToShow = $categories
            ->where("count_to_summary", true)->get()
            ->pluck("id")->toArray();

        $meansToShow = $means
            ->where("count_to_summary", true)->get()
            ->pluck("id")->toArray();

        $means = $means->addSelect("name", "first_entry_date", "first_entry_amount")->get();
        $categories = $categories->addSelect("name", "count_to_summary", "start_date", "end_date")->get();

        $income = auth()->user()->income()
            ->where("currency_id", $currency->id)
            ->select("date", "category_id", "mean_id", DB::raw("round(amount * price, 2) AS value"))
            ->get();

        $outcome = auth()->user()->outcome()
            ->where("currency_id", $currency->id)
            ->select("date", "category_id", "mean_id", DB::raw("round(amount * price, 2) AS value"))
            ->get();

        $currentBalance = $this->getBalance($income, $outcome, $means, $categories, $meansToShow, $categoriesToShow);
        $last30Days = $this->getLast30DaysBalance($income, $outcome, $meansToShow, $categoriesToShow);

        $charts = $this->getCharts("/dashboard");

        return response()->json(compact("currentBalance", "last30Days", "charts"));
    }
}
