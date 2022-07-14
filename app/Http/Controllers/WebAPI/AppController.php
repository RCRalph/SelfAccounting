<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

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
            now()->addMinutes(15),
            function () {
                $currencies = $this->getCurrencies()->toArray();
                $lastCurrencies = $this->getLastUsedCurrencies();

                foreach (array_reverse($lastCurrencies) as $currency) {
                    $index = array_search($currency, array_column($currencies, "id"));
                    array_unshift($currencies, $currencies[$index]);
                    array_splice($currencies, $index + 1, 1);
                }

                $retArr = [
                    "user" => auth()->user()->only("id", "username", "darkmode", "profile_picture_link"),
                    "currencies" => $currencies
                ];

                //$retArr["bundle_info"] = $this->BUNDLE_INFO_LIST;

                // Select bundles available to the user
                /*$retArr["bundles"] = auth()->user()->premiumBundles
                    ->merge(auth()->user()->bundles)
                    ->filter(
                        fn ($item) => $item->pivot->enabled === null ? true : $item->pivot->enabled
                    );*/

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
