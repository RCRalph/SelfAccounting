<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Currency;

use App\Rules\Extensions\Cash\CorrectCashMean;
use App\Rules\Extensions\Cash\CorrectCashCurrency;

class CashController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "extension:cashan"]);
    }

    public function index(Currency $currency)
    {
        // Cash as id: value array
        $cash = $currency->cash()->select("id", "value")
            ->orderBy("value", "DESC")->get()
            ->mapWithKeys(fn ($item) => [$item["id"] => $item["value"] * 1]);

        // Means of payment used as cash
        $cashMean = auth()->user()->cashMeans()
            ->where("currency_id", $currency->id)
            ->pluck("mean_of_payments.id")
            ->first();

        // Owned cash as id: amount array
        $ownedCash = auth()->user()->cash()
            ->where("currency_id", $currency->id)->get()
            ->mapWithKeys(fn ($item) => [$item->id => $item->pivot->amount]);

        return response()->json(compact("cash", "cashMean", "ownedCash"));
    }

    public function list(Currency $currency)
    {
        $cash = $currency->cash()->select("id", "value")
            ->orderBy("value", "DESC")->get()
            ->mapWithKeys(fn ($item) => [$item["id"] => $item["value"] * 1]);

        $cashMean = auth()->user()->cashMeans()
            ->where("currency_id", $currency->id)
            ->pluck("mean_of_payments.id")
            ->toArray();

        $cashMean = $cashMean ? $cashMean[0] : null;

        $ownedCash = auth()->user()->cash()->where("currency_id", $currency->id)->get()
            ->mapWithKeys(fn ($item) => [$item->id => $item->pivot->amount]);

        $means = auth()->user()->meansOfPayment()
            ->select("id", "name", "first_entry_amount")
            ->where("currency_id", $currency->id)
            ->get();

        foreach ($means as $mean) {
            $balance = $mean->first_entry_amount;

            $income = auth()->user()->income()
                ->where("mean_id", $mean->id)
                ->where("currency_id", $currency->id)
                ->select(DB::raw("round(amount * price, 2) AS value"))
                ->get();

            foreach ($income as $item) {
                $balance += $item->value;
            }

            $outcome = auth()->user()->outcome()
                ->where("mean_id", $mean->id)
                ->where("currency_id", $currency->id)
                ->select(DB::raw("round(amount * price, 2) AS value"))
                ->get();

            foreach ($outcome as $item) {
                $balance -= $item->value;
            }

            $mean->balance = round($balance, 2);
        }

        $means = $means
            ->prepend(["id" => null, "name" => "N/A", "balance" => 0])
            ->map(fn ($item) => [
                "id" => $item["id"],
                "name" => $item["name"],
                "balance" => $item["balance"]
            ]);

        return response()->json(compact("cash", "cashMean", "ownedCash", "means"));
    }

    public function update(Currency $currency)
    {
        if (request()->has("mean")) {
            $mean = request()->validate([
                "mean" => ["present", "nullable", "exists:mean_of_payments,id", new CorrectCashMean($currency)]
            ])["mean"];

            $currentMean = auth()->user()->cashMeans()->where("currency_id", $currency->id)->first();

            if ($currentMean) {
                $currentMean = $currentMean->id;
            }

            if ($mean != $currentMean) {
                if ($currentMean) {
                    auth()->user()->cashMeans()->detach($currentMean);
                }

                if ($mean) {
                    auth()->user()->cashMeans()->attach($mean);
                }
            }
        }

        if (request()->has("cash")) {
            $cash = request()->validate([
                "cash" => ["required", "array"],
                "cash.*.amount" => ["present", "nullable", "integer", "min:0", "max:1e7"],
                "cash.*.id" => ["required", "distinct", "exists:cash,id", new CorrectCashCurrency($currency)],
            ])["cash"];

            foreach ($cash as $i => $item) {
                if (!$item["amount"]) {
                    unset($cash[$i]);
                }
            }

            $cashIDs = array_column($cash, "id");

            $IDsToDetach = auth()->user()->cash()
                ->where("currency_id", $currency->id)
                ->whereNotIn("cash.id", $cashIDs)
                ->pluck("cash.id")->toArray();

            auth()->user()->cash()->detach($IDsToDetach);

            foreach ($cash as $item) {
                if (!in_array($item["id"], $cashIDs)) {
                    continue;
                }

                if (auth()->user()->cash()->where("cash.id", $item["id"])->exists()) {
                    auth()->user()->cash()->updateExistingPivot($item["id"], [
                        "amount" => $item["amount"]
                    ]);
                }
                else {
                    auth()->user()->cash()->attach($item["id"], [
                        "amount" => $item["amount"]
                    ]);
                }
            }
        }

        return response("");
    }
}
