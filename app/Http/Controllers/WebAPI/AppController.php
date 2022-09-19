<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Models\Chart;
use App\Models\Extension;
use App\Models\Tutorial;

class AppController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function getAppStartData()
    {
        return Cache::remember(
            "app-data-" . auth()->user()->id,
            now()->addMinutes(env("APP_DEBUG") ? 0 : 15),
            function () {
                // Update user's last page visit
                auth()->user()->update([
                    "last_page_visit" => Carbon::now()
                ]);

                $currencies = $this->getCurrencies()->toArray();
                $lastCurrencies = $this->getLastUsedCurrencies();

                foreach (array_reverse($lastCurrencies) as $currency) {
                    $index = array_search($currency, array_column($currencies, "id"));
                    array_unshift($currencies, $currencies[$index]);
                    array_splice($currencies, $index + 1, 1);
                }

                return [
                    "user" => auth()->user()->only("id", "username", "darkmode", "profile_picture_link", "admin", "hide_all_tutorials"),
                    "currencies" => $currencies,
                    "charts" => $this->getCharts("/"),
                    "tutorials" => Tutorial::select("route")->pluck("route"),
                    "disabledTutorials" => auth()->user()->disabledTutorials->pluck("route"),
                    "extensions" => Extension::all()->makeHidden(["id", "created_at", "updated_at", "description", "thumbnail"]),
                    "ownedExtensions" => Extension::whereIn("code", auth()->user()->extensionCodes)
                        ->orderBy("title")
                        ->pluck("code")
                ];
            }
        );
    }

    public function index()
    {
        $data = $this->getAppStartData();
        return response()->json($data);
    }
}
