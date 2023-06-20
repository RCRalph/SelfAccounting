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

    public function addNamesToPaginatedTransactionsItems($items, Currency $currency)
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
                $expenseTitles = auth()->user()->expenses()->pluck("title");

                return $incomeTitles
                    ->merge($expenseTitles)
                    ->unique()
                    ->values();
            }
        );
    }

    public function getTypeRelation($type = null)
    {
        $type = $type ? $type : request()->type;

        if (!in_array($type, ["income", "expenses"])) {
            abort(500, "Unspecified type");
        }

        return $type == "income" ?
            auth()->user()->income() :
            auth()->user()->expenses();
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
