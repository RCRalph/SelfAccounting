<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Chart;
use App\Models\Extension;
use App\Models\Tutorial;
use App\Models\Currency;

class AppController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function getLastUsedCurrencies()
    {
        $income = auth()->user()->income()
            ->select("currency_id", DB::raw("MAX(updated_at) AS last_accessed"))
            ->groupBy("currency_id");

        $expenses = auth()->user()->expenses()
            ->select("currency_id", DB::raw("MAX(updated_at) AS last_accessed"))
            ->groupBy("currency_id");

        return $income->union($expenses)->get()
            ->sortByDesc("last_accessed")
            ->unique("currency_id")
            ->pluck("currency_id");
    }

    private function showPremiumExpiredDialog() {
        if (auth()->user()->account_type != "Normal") {
            return false;
        }

        return auth()->user()->premium_expiration
            ->isAfter(auth()->user()->last_page_visit);
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

                return [
                    "user" => auth()->user()->only("id", "username", "darkmode", "profile_picture_link", "admin", "hide_all_tutorials"),
                    "charts" => $this->getCharts("/"),
                    "tutorials" => Tutorial::select("route")->pluck("route"),
                    "disabledTutorials" => auth()->user()->disabledTutorials->pluck("route"),
                    "extensions" => Extension::select("code", "title", "icon", "directory")->orderBy("title")->get(),
                    "ownedExtensions" => Extension::whereIn("code", auth()->user()->extensionCodes)
                        ->orderBy("title")
                        ->pluck("code")
                ];
            }
        );
    }

    public function index()
    {
        return response()->json([
            ...$this->getAppStartData(),
            "currencies" => auth()->user()->lastUsedCurrencies,
            "premiumExpired" => $this->showPremiumExpiredDialog()
        ]);
    }
}
