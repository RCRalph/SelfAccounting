<?php

namespace App\Http\Controllers\Bundles\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Currency;
use App\Cash;

class CashController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "bundle:cashan"]);
    }

    public function index()
    {
        $currencies = Currency::all();
        $lastCurrency = $this->getLastCurrency();
        $cash = Cash::all()
            ->sortByDesc("value")
            ->groupBy("currency_id");

        $means = auth()->user()->meansOfPayment
            ->map(fn ($item) => $item->only("name", "currency_id", "id"))
            ->groupBy("currency_id");

        $cashMeansList = auth()->user()->cash_means
            ->map(fn ($item) => $item->only("currency_id", "id"))
            ->groupBy("currency_id");

        $cashMeans = [];
        foreach ($cashMeansList as $currency => $value) {
            $cashMeans[$currency] = $value[0]["id"];
        }

        foreach ($currencies as $currency) {
            if (!isset($cashMeans[$currency->id])) {
                $cashMeans[$currency->id] = null;
            }
        }

		$usersCash = [];
		foreach (auth()->user()->cash as $userCash) {
			$usersCash[$userCash->pivot->cash_id] = $userCash->pivot->amount;
		}

        return response()->json(compact("currencies", "lastCurrency", "cash", "means", "cashMeans", "usersCash"));
    }
}
