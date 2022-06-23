<?php

namespace App\Http\Controllers\Bundles\Web_Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Rules\UniqueMeanCurrency;
use App\Rules\MeanBelongsToUser;

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
        $currencies = $this->getCurrencies();
        $lastCurrency = $this->getLastCurrency();
        $cash = $this->getCash();

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

        $cashMeans = $this->getCashMeans();

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
            "cashMeans.*" => ["required", "exists:mean_of_payments,id", new UniqueMeanCurrency, new MeanBelongsToUser]
        ]);

		// Insert cash
		$usersCashIDsInDB = auth()->user()->cash
			->map(fn ($item) => $item["id"])
			->toArray();
		$usersCashIDs = array_map(fn ($item) => $item["id"] * 1, $data["usersCash"]);

		$IDsToRemove = array_values(array_diff($usersCashIDsInDB, $usersCashIDs));
        $IDsToUpdate = array_merge(
            array_values(array_intersect($usersCashIDsInDB, $usersCashIDs)),
            array_values(array_diff($usersCashIDs, $usersCashIDsInDB))
        );

		auth()->user()->cash()->detach($IDsToRemove);

		$usersCashIDsToAmounts = [];
		foreach ($data["usersCash"] as $cash) {
			$usersCashIDsToAmounts[$cash["id"]] = $cash["amount"];
		}

		foreach ($IDsToUpdate as $ID) {
			if (auth()->user()->cash->contains($ID)) {
				auth()->user()->cash()
					->updateExistingPivot($ID, ["amount" => $usersCashIDsToAmounts[$ID]]);
			}
			else {
				auth()->user()->cash()
					->attach($ID, ["amount" => $usersCashIDsToAmounts[$ID]]);
			}
		}

		// Insert cash means
        $cashMeansInDB = auth()->user()->cashMeans
            ->map(fn ($item) => $item["id"])
            ->toArray();

        $IDsToInsert = array_values(array_diff($data["cashMeans"], $cashMeansInDB));
        $IDsToRemove = array_values(array_diff($cashMeansInDB, $data["cashMeans"]));

        auth()->user()->cashMeans()->detach($IDsToRemove);
        auth()->user()->cashMeans()->attach($IDsToInsert);

		return response()->json(compact("data"));
    }
}
