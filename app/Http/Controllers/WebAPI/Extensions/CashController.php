<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Currency;

use App\Rules\Extensions\Cash\CorrectCashAccount;
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
            ->mapWithKeys(fn($item) => [$item["id"] => $item["value"] * 1]);

        // Account used as cash
        $cashAccount = auth()->user()->cashAccounts()
            ->where("currency_id", $currency->id)
            ->pluck("accounts.id")
            ->first();

        // Owned cash as id: amount array
        $ownedCash = auth()->user()->cash()
            ->where("currency_id", $currency->id)->get()
            ->mapWithKeys(fn($item) => [$item->id => $item->pivot->amount]);

        return response()->json(compact("cash", "cashAccount", "ownedCash"));
    }

    public function list(Currency $currency)
    {
        // Cash as id: value array
        $cash = $currency->cash()->select("id", "value")
            ->get()
            ->mapWithKeys(fn($item) => [$item["id"] => $item["value"] * 1]);

        // Account used as cash
        $cashAccount = auth()->user()->cashAccounts()
            ->where("currency_id", $currency->id)
            ->pluck("accounts.id")
            ->first();

        // Owned cash as id: amount array
        $ownedCash = auth()->user()->cash()
            ->where("currency_id", $currency->id)
            ->get()
            ->mapWithKeys(fn($item) => [$item->id => $item->pivot->amount]);

        // Accounts for given currency
        $accounts = auth()->user()->accounts()
            ->select("id", "name", "icon", "start_balance")
            ->where("currency_id", $currency->id)
            ->get();

        foreach ($accounts as $account) {
            $account->value = $account->balance();
        }

        $accounts = $accounts
            ->prepend(["id" => null, "icon" => null, "name" => "N/A", "value" => 0])
            ->map(fn($item) => [
                "id" => $item["id"],
                "icon" => $item["icon"],
                "name" => $item["name"],
                "balance" => $item["value"]
            ]);

        return response()->json(compact("cash", "cashAccount", "ownedCash", "accounts"));
    }

    public function update(Currency $currency)
    {
        if (request()->has("account")) {
            $account = request()->validate([
                "account" => ["present", "nullable", "exists:accounts,id", new CorrectCashAccount($currency)]
            ])["account"];

            $currentAccount = auth()->user()->cashAccounts()->where("currency_id", $currency->id)->first();

            if ($currentAccount) {
                $currentAccount = $currentAccount->id;
            }

            if ($account != $currentAccount) {
                if ($currentAccount) {
                    auth()->user()->cashAccounts()->detach($currentAccount);
                }

                if ($account) {
                    auth()->user()->cashAccounts()->attach($account);
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
                } else {
                    auth()->user()->cash()->attach($item["id"], [
                        "amount" => $item["amount"]
                    ]);
                }
            }
        }

        return response("");
    }
}
