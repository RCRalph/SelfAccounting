<?php

namespace App\Http\Controllers\Bundles\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Rules\UniqueMeanCurrency;

use App\Currency;
use App\Cash;
use App\Income;
use App\Outcome;

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
            ->map(fn ($item) => $item->only("name", "currency_id", "id", "first_entry_amount"))
            ->groupBy("currency_id")
            ->toArray();

        foreach ($means as $currency => $items) {
            foreach ($items as $i => $item) {
                $balance = 0;
                $income = Income::where("mean_id", $item["id"])->get();
                foreach ($income as $item1) {
                    $balance += $item1->amount * $item1->price;
                }

                $outcome = Outcome::where("mean_id", $item["id"])->get();
                foreach ($outcome as $item1) {
                    $balance -= $item1->amount * $item1->price;
                }

                $means[$currency][$i]["balance"] = round($balance + $means[$currency][$i]["first_entry_amount"], 2);
                unset($means[$currency][$i]["first_entry_amount"]);
            }
        }

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

    public function saveCashAndMeans()
    {
        $data = request()->validate([
            // Validate user's cash
            "usersCash" => ["present", "array"],
            "usersCash.*.amount" => ["required", "integer", "min:1", "max:9223372036854775807"],
            "usersCash.*.id" => ["required", "distinct", "exists:cash,id"],

            // Validate cash means
            "cashMeans" => ["present", "array"],
            "cashMeans.*" => ["required", "exists:mean_of_payments,id", new UniqueMeanCurrency]
        ]);
        dd($data);
    }
}
