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

    public function getLastUsedCurrencies()
    {
        return Cache::remember(
            "last-used-currencies-" . auth()->user()->id,
            now()->addMinutes(15),
            function () {
                $data = auth()->user()->income
                    ->concat(auth()->user()->outcome)
                    ->sortBy("updated_at")
                    ->groupBy("currency_id");

                $retArr = [];
                foreach ($data as $currency => $entries) {
                    $retArr[$currency] = collect($entries)->last()["updated_at"];
                }

                return collect($retArr)->sortDesc()->keys()->toArray();
            }
        );
    }

    public function getBalance($income, $outcome, $means, $categories, $meansToShow, $categoriesToShow)
    {
        $incomeByMeans = $income
            ->whereIn("mean_id", $meansToShow)
            ->groupBy("mean_id")
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

        $outcomeByMeans = $outcome
            ->whereIn("mean_id", $meansToShow)
            ->groupBy("mean_id")
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

        $balanceByMeans = $incomeByMeans;
        foreach ($outcomeByMeans as $mean => $balance) {
            if (array_key_exists($mean, $balanceByMeans)) {
                $balanceByMeans[$mean] -= $balance;
            }
            else {
                $balanceByMeans[$mean] = -$balance;
            }
        }

        $balanceByCategories = $outcome
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
        $foundMeanIDs = [];

        foreach ($balanceByMeans as $meanID => $balance) {
            $foundMean = $means->where("id", $meanID)->first();

            array_push($currentBalance, [
                "mean_id" => $foundMean->id,
                "name" => $foundMean->name,
                "balance" => $balance + $foundMean->first_entry_amount
            ]);

            array_push($foundMeanIDs, $foundMean->id);
        }

        // Add means without any entries
        foreach ($means->whereNotIn("id", $foundMeanIDs) as $mean) {
            array_push($currentBalance, [
                "mean_id" => $mean->id,
                "name" => $mean->name,
                "balance" => $mean->first_entry_amount * 1 // Convert string to number
            ]);
        }

        // Add categories marked as count to summary
        foreach ($balanceByCategories as $categoryID => $balance) {
            $foundCategory = $categories->where("id", $categoryID)->first();

            array_push($currentBalance, [
                "category_id" => $foundCategory->id,
                "name" => $foundCategory->name,
                "balance" => $balance
            ]);
        }

        // Sort by balance DESC
        usort($currentBalance, function ($a, $b) {
            return $b["balance"] - $a["balance"];
        });

        // Round to 2 decimal places
        foreach ($currentBalance as $i => $val) {
            $currentBalance[$i]["balance"] = round($val["balance"], 2);
        }

        return $currentBalance;
    }

    public function addNamesToPaginatedIOItems($items, Currency $currency)
    {
        $paginatedData = $items->getCollection()->toArray();

        $categories = auth()->user()->categories()
            ->select("id", "name")
            ->where("currency_id", $currency->id)
            ->get()
            ->mapWithKeys(fn ($item) => [$item["id"] => $item["name"]]);

        $means = auth()->user()->meansOfPayment()
            ->select("id", "name")
            ->where("currency_id", $currency->id)
            ->get()
            ->mapWithKeys(fn ($item) => [$item["id"] => $item["name"]]);

        foreach ($paginatedData as $i => $item) {
            $paginatedData[$i]["amount"] *= 1;
            $paginatedData[$i]["price"] *= 1;
            $paginatedData[$i]["value"] *= $item["type"] ?? 1;
            $paginatedData[$i]["category"] = $categories[$item["category_id"]] ?? "N/A";
            $paginatedData[$i]["mean"] = $means[$item["mean_id"]] ?? "N/A";

            unset($paginatedData[$i]["type"], $paginatedData[$i]["category_id"], $paginatedData[$i]["mean_id"]);
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
                $outcomeTitles = auth()->user()->outcome()->pluck("title");

                return $incomeTitles
                    ->merge($outcomeTitles)
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

        if (!in_array($type, ["income", "outcome"])) {
            abort(500, "Unspecified type");
        }

        return $type == "income" ?
            auth()->user()->income() :
            auth()->user()->outcome();
    }

    public function getCharts($route) {
        return Chart::select("charts.id", "charts.name")
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
