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
            ->select("id", "date", "title", "amount", "price", "category_id", "mean_id", DB::raw("1 AS type"));
        $outcome = auth()->user()->outcome()
            ->where("currency_id", $currency->id)
            ->select("id", "date", "title", "amount", "price", "category_id", "mean_id", DB::raw("-1 AS type"));

        /*if ($filteredDates != null) {
            $income = $income->whereIn("date", $filteredDates);
            $outcome = $outcome->whereIn("date", $filteredDates);
        }

        if ($searchTerm != null) {
            $income = $income->where("title", "ilike", $searchTerm);
            $outcome = $outcome->where("title", "ilike", $searchTerm);
        }*/

        $items = $income
            ->union($outcome)
            ->orderBy("date", "desc")
            ->orderBy("title")
            ->orderBy("amount")
            ->orderBy("price")
            ->paginate(15);

        $paginatedData = $items->getCollection()->toArray();

        $categories = auth()->user()->categories()
            ->where("currency_id", $currency->id)
            ->select("id", "name")
            ->get()
            ->keyBy("id");

        $means = auth()->user()->meansOfPayment()
            ->where("currency_id", $currency->id)
            ->select("id", "name")
            ->get()
            ->keyBy("id");

        foreach ($paginatedData as $i => $item) {
            $paginatedData[$i]["amount"] *= 1;
            $paginatedData[$i]["price"] *= 1;
            $paginatedData[$i]["value"] = round($item["amount"] * $item["price"] * $item["type"], 2);
            $paginatedData[$i]["category"] = $categories[$item["category_id"]]->name ?? "N/A";
            $paginatedData[$i]["mean"] = $means[$item["mean_id"]]->name ?? "N/A";

            unset($paginatedData[$i]["type"], $paginatedData[$i]["category_id"], $paginatedData[$i]["mean_id"]);
        }

        $items->setCollection(collect($paginatedData));

        return response()->json(compact("items"));
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

        $charts = Chart::all();

        return response()->json(compact("currentBalance", "last30Days", "charts"));
    }
}
