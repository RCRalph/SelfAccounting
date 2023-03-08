<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

use App\Models\Currency;
use App\Models\Chart;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getCurrencies()
    {
        return Cache::rememberForever("currencies", fn () => Currency::all());
    }

    public function getBalance($income, $expences, $transfers, $accounts, $categories, $accountsToShow, $categoriesToShow)
    {
        $incomeByAccounts = $income
            ->whereIn("account_id", $accountsToShow)
            ->groupBy("account_id")
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

        $expencesByAccounts = $expences
            ->whereIn("account_id", $accountsToShow)
            ->groupBy("account_id")
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

        $balanceByAccounts = $incomeByAccounts;
        foreach ($expencesByAccounts as $account => $balance) {
            if (array_key_exists($account, $balanceByAccounts)) {
                $balanceByAccounts[$account] -= $balance;
            }
            else {
                $balanceByAccounts[$account] = -$balance;
            }
        }

        // Add incoming transfers
        $transfersInByAccounts = $transfers
            ->whereIn("target_account_id", $accounts->pluck("id"))
            ->groupBy("target_account_id")
            ->map(fn ($item) => $item->sum("target_value"))
            ->toArray();

        foreach ($transfersInByAccounts as $account => $balance) {
            if (array_key_exists($account, $balanceByAccounts)) {
                $balanceByAccounts[$account] += $balance;
            }
            else {
                $balanceByAccounts[$account] = $balance;
            }
        }

        // Add outgoing transfers
        $transfersOutByAccounts = $transfers
            ->whereIn("source_account_id", $accounts->pluck("id"))
            ->groupBy("source_account_id")
            ->map(fn ($item) => $item->sum("source_value"))
            ->toArray();

        foreach ($transfersOutByAccounts as $account => $balance) {
            if (array_key_exists($account, $balanceByAccounts)) {
                $balanceByAccounts[$account] -= $balance;
            }
            else {
                $balanceByAccounts[$account] = -$balance;
            }
        }

        $balanceByCategories = $expences
            ->whereIn("category_id", $categoriesToShow)
            ->groupBy("category_id");

        foreach ($balanceByCategories->keys() as $categoryID) {
            $category = $categories->find($categoryID);

            foreach ($balanceByCategories[$categoryID] as $key => $value) {
                $limitedByStart = (
                    $category->start_date != null &&
                    strtotime($value["date"]) < strtotime($category->start_date)
                );

                $limitedByEnd = (
                    $category->end_date != null &&
                    strtotime($value["date"]) > strtotime($category->end_date)
                );

                if ($limitedByStart || $limitedByEnd) {
                    unset($balanceByCategories[$categoryID][$key]);
                }
            }
        }

        $balanceByCategories = $balanceByCategories
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

        $currentBalance = [];
        $foundAccountIDs = [];

        foreach ($balanceByAccounts as $accountID => $balance) {
            $foundAccount = $accounts->firstWhere("id", $accountID);

            array_push($currentBalance, [
                "icon" => $foundAccount->icon,
                "account_id" => $foundAccount->id,
                "name" => $foundAccount->name,
                "balance" => $balance + $foundAccount->start_balance
            ]);

            array_push($foundAccountIDs, $foundAccount->id);
        }

        // Add accounts without any entries
        foreach ($accounts->whereNotIn("id", $foundAccountIDs) as $account) {
            array_push($currentBalance, [
                "icon" => $account->icon,
                "account_id" => $account->id,
                "name" => $account->name,
                "balance" => $account->start_balance * 1 // Convert string to number
            ]);
        }

        // Add categories marked as count to summary
        foreach ($balanceByCategories as $categoryID => $balance) {
            $foundCategory = $categories->firstWhere("id", $categoryID);

            array_push($currentBalance, [
                "icon" => $foundCategory->icon,
                "category_id" => $foundCategory->id,
                "name" => $foundCategory->name,
                "balance" => $balance
            ]);
        }

        // Sort by balance DESC
        usort($currentBalance, function ($a, $b) {
            if ($b["balance"] == $a["balance"]) {
                return 0;
            }

            return $b["balance"] - $a["balance"] < 0 ? -1 : 1;
        });

        // Round to 2 decimal places
        foreach ($currentBalance as $i => $val) {
            $balance = round($val["balance"], 2);
            $currentBalance[$i]["balance"] = $balance == 0 ? 0 : $balance;
        }

        return $currentBalance;
    }

    public function addNamesToPaginatedIncomeOrExpencesItems($items, Currency $currency)
    {
        $paginatedData = $items->getCollection()->toArray();

        $categories = auth()->user()->categories()
            ->select("id", "name", "icon")
            ->where("currency_id", $currency->id)
            ->get()
            ->mapWithKeys(fn ($item) => [$item["id"] => [
                "name" => $item["name"],
                "icon" => $item["icon"]
            ]]);

        $accounts = auth()->user()->accounts()
            ->select("id", "name", "icon")
            ->where("currency_id", $currency->id)
            ->get()
            ->mapWithKeys(fn ($item) => [$item["id"] => [
                "name" => $item["name"],
                "icon" => $item["icon"]
            ]]);

        foreach ($paginatedData as $i => $item) {
            $paginatedData[$i]["amount"] *= 1;
            $paginatedData[$i]["price"] *= 1;
            $paginatedData[$i]["value"] *= $item["type"] ?? 1;

            $paginatedData[$i]["category"] = [
                "name" => $categories[$item["category_id"]]["name"] ?? "N/A",
                "icon" => $categories[$item["category_id"]]["icon"] ?? null
            ];

            $paginatedData[$i]["account"] = [
                "name" => $accounts[$item["account_id"]]["name"] ?? "N/A",
                "icon" => $accounts[$item["account_id"]]["icon"] ?? null
            ];

            unset($paginatedData[$i]["type"], $paginatedData[$i]["category_id"], $paginatedData[$i]["account_id"]);
        }

        $items->setCollection(collect($paginatedData));

        return $items;
    }

    public function getTitles()
    {
        return Cache::remember(
            "titles_" . auth()->user()->id,
            now()->addMinutes(15),
            function () {
                $incomeTitles = auth()->user()->income()->pluck("title");
                $expenceTitles = auth()->user()->expences()->pluck("title");

                return $incomeTitles
                    ->merge($expenceTitles)
                    ->unique()
                    ->values();
            }
        );
    }

    public function getColors($numberOfColors)
    {
        if (!$numberOfColors) {
            return [];
        }

        $circleShift = rand(1, 360);
        $step = round(360 / $numberOfColors);

        $retArr = [];
        for ($i = 0; $i < $numberOfColors; $i++) {
            $h = ($circleShift + $i * $step) % 360;
            $s = rand(80, 100);
            $l = rand(40, 60);
            array_push($retArr, "hsl($h, $s%, $l%)");
        }

        return $retArr;
    }

    public function getTypeRelation($type = null)
    {
        $type = $type ? $type : request()->type;

        if (!in_array($type, ["income", "expences"])) {
            abort(500, "Unspecified type");
        }

        return $type == "income" ?
            auth()->user()->income() :
            auth()->user()->expences();
    }

    public function getCharts($route) {
        return Chart::select("charts.id", "charts.name", "charts.type")
            ->join("chart_routes", "chart_routes.chart_id", "=", "charts.id")
            ->where("chart_routes.route", $route)
            ->get();
    }

    public function removeFile($disk, $directory, $name)
    {
        Storage::disk($disk)->delete("$directory/$name");
    }

    public function saveImage($disk, $image, $directory, $fileToRemove = null, $fitResolution = [])
    {
        $img = Image::make($image);
        if (count($fitResolution) == 2) {
            $img->fit($fitResolution[0], $fitResolution[1]);
        }

        do {
            $filename = Str::random(50) . "." . $image->extension();
        } while (Storage::disk($disk)->has("$directory/$filename"));

        if ($fileToRemove) {
            $this->removeFile($disk, $directory, $fileToRemove);
        }

        Storage::disk($disk)->put("$directory/$filename", $img->stream());

        return $filename;
    }
}
