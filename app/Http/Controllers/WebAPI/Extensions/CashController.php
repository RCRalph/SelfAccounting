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
            ->mapWithKeys(fn ($item) => [$item["id"] => $item["value"] * 1]);

        // Accounts used as cash
        $cashAccount = auth()->user()->cashAccounts()
            ->where("currency_id", $currency->id)
            ->pluck("accounts.id")
            ->first();

        // Owned cash as id: amount array
        $ownedCash = auth()->user()->cash()
            ->where("currency_id", $currency->id)->get()
            ->mapWithKeys(fn ($item) => [$item->id => $item->pivot->amount]);

        return response()->json(compact("cash", "cashAccount", "ownedCash"));
    }

    public function list(Currency $currency)
    {
        $cash = $currency->cash()->select("id", "value")
            ->orderBy("value", "DESC")->get()
            ->mapWithKeys(fn ($item) => [$item["id"] => $item["value"] * 1]);

        $cashAccount = auth()->user()->cashAccounts()
            ->where("currency_id", $currency->id)
            ->pluck("accounts.id")
            ->toArray();

        $cashAccount = $cashAccount ? $cashAccount[0] : null;

        $ownedCash = auth()->user()->cash()->where("currency_id", $currency->id)->get()
            ->mapWithKeys(fn ($item) => [$item->id => $item->pivot->amount]);

        $accounts = auth()->user()->accounts()
            ->select("id", "name", "start_balance")
            ->where("currency_id", $currency->id)
            ->get();

        foreach ($accounts as $account) {
            $balance = $account->start_balance;

            $income = auth()->user()->income()
                ->where("account_id", $account->id)
                ->where("currency_id", $currency->id)
                ->select(DB::raw("round(amount * price, 2) AS value"))
                ->get();

            foreach ($income as $item) {
                $balance += $item->value;
            }

            $outcome = auth()->user()->outcome()
                ->where("account_id", $account->id)
                ->where("currency_id", $currency->id)
                ->select(DB::raw("round(amount * price, 2) AS value"))
                ->get();

            foreach ($outcome as $item) {
                $balance -= $item->value;
            }

            $account->balance = round($balance, 2);
        }

        $accounts = $accounts
            ->prepend(["id" => null, "name" => "N/A", "balance" => 0])
            ->map(fn ($item) => [
                "id" => $item["id"],
                "name" => $item["name"],
                "balance" => $item["balance"]
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
