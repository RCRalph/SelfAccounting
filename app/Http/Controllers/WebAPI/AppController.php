<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Bundle;

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
                $currencies = $this->getCurrencies()->toArray();
                $lastCurrencies = $this->getLastUsedCurrencies();

                foreach (array_reverse($lastCurrencies) as $currency) {
                    $index = array_search($currency, array_column($currencies, "id"));
                    array_unshift($currencies, $currencies[$index]);
                    array_splice($currencies, $index + 1, 1);
                }

                $retArr = [
                    "user" => auth()->user()->only("id", "username", "darkmode", "profile_picture_link", "admin", "hide_all_tutorials"),
                    "currencies" => $currencies,
                    "bundles" => Bundle::all()->makeHidden("id", "created_at", "updated_at"),
                    "ownedBundles" => Bundle::whereIn("id", auth()->user()->bundleIDs)
                        ->orderBy("title")
                        ->pluck("code")
                ];

                // Update user's last page visit
                auth()->user()->update([
                    "last_page_visit" => Carbon::now()
                ]);

                return $retArr;
            }
        );
    }

    public function index()
    {
        $data = $this->getAppStartData();
        return response()->json($data);
    }
}
