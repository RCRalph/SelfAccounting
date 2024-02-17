<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Currency;
use App\Models\Transfer;
use App\Models\Chart;

use App\Rules\EqualArrayLength;
use App\Rules\Transfer\CorrectTransferDate;
use App\Rules\Transfer\ValidTransferAccount;
use App\Rules\Extensions\Cash\CorrectTransferCashCurrency;
use App\Rules\Extensions\Cash\ValidCashAmount;

class TransfersController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function addNamesToPaginatedItems($items)
    {
        $paginatedData = $items->getCollection()->toArray();

        $currencies = Currency::all()->mapWithKeys(fn($item) => [$item["id"] => $item["ISO"]]);

        $accounts = auth()->user()->accounts()
            ->select("id", "name", "icon", "currency_id")
            ->where(function ($query) use ($paginatedData) {
                $query->whereIn("id", array_column($paginatedData, "source_account_id"))
                    ->orWhereIn("id", array_column($paginatedData, "target_account_id"));
            })
            ->get()
            ->mapWithKeys(fn($item) => [$item["id"] => [
                "name" => $item["name"],
                "icon" => $item["icon"],
                "currency" => $currencies[$item["currency_id"]]
            ]]);

        foreach ($paginatedData as $i => $item) {
            $paginatedData[$i]["source_value"] *= 1;
            $paginatedData[$i]["source_account"] = [
                "name" => $accounts[$item["source_account_id"]]["name"],
                "icon" => $accounts[$item["source_account_id"]]["icon"] ?? null,
                "currency" => $accounts[$item["source_account_id"]]["currency"]
            ];

            $paginatedData[$i]["target_value"] *= 1;
            $paginatedData[$i]["target_account"] = [
                "name" => $accounts[$item["target_account_id"]]["name"],
                "icon" => $accounts[$item["target_account_id"]]["icon"] ?? null,
                "currency" => $accounts[$item["target_account_id"]]["currency"]
            ];

            unset($paginatedData[$i]["source_account_id"], $paginatedData[$i]["target_account_id"]);
        }

        $items->setCollection(collect($paginatedData));

        return $items;
    }

    public function index(Currency $currency)
    {
        $accounts = auth()->user()->accounts()
            ->where("currency_id", $currency->id)
            ->orderBy("name")
            ->get();

        $charts = Chart::route("/transfers");

        return response()->json(compact("accounts", "charts"));
    }

    public function list(Currency $currency)
    {
        $accounts = auth()->user()->accounts()
            ->where("currency_id", $currency->id)
            ->pluck("id");

        $items = auth()->user()->transfers()
            ->select("id", "date", "source_account_id", "source_value", "target_account_id", "target_value")
            ->where(function ($query) use ($accounts) {
                $query->whereIn("source_account_id", $accounts)
                    ->orWhereIn("target_account_id", $accounts);
            });

        $fields = ["date", "source_value", "target_value"];

        $data = request()->validate([
            "dates" => ["nullable", "array"],
            "dates.*" => ["required", "date", "distinct"],
            "source_accounts" => ["nullable", "array"],
            "source_accounts.*" => ["required", "integer", "exists:accounts,id"],
            "target_accounts" => ["nullable", "array"],
            "target_accounts.*" => ["required", "integer", "exists:accounts,id"],
            "orderFields" => ["nullable", "array", new EqualArrayLength("orderDirections")],
            "orderFields.*" => ["required", "string", "in:" . implode(",", $fields), "distinct"],
            "orderDirections" => ["nullable", "array", new EqualArrayLength("orderFields")],
            "orderDirections.*" => ["nullable", "string", "in:asc,desc"]
        ]);

        if (isset($data["dates"])) {
            $items->whereIn("date", $data["dates"]);
        }

        if (isset($data["source_accounts"])) {
            $items->whereIn("source_account_id", $data["source_accounts"]);
        }

        if (isset($data["target_accounts"])) {
            $items->whereIn("target_account_id", $data["target_accounts"]);
        }

        if (isset($data["orderFields"]) && isset($data["orderDirections"])) {
            foreach ($data["orderFields"] as $i => $item) {
                $fields = array_diff($fields, [$item]);
                $items = $items->orderBy($item, $data["orderDirections"][$i] ?? "asc");
            }
        }

        foreach ($fields as $field) {
            $items = $items->orderBy($field, $field == "date" ? "desc" : "asc");
        }

        $items = $this->addNamesToPaginatedItems($items->paginate(20));

        return response()->json(compact("items"));
    }

    public function data()
    {
        $accounts = auth()->user()->accounts()
            ->select("id", "icon", "name", "currency_id", "start_date")
            ->orderBy("name")
            ->get()
            ->groupBy("currency_id");

        $availableCurrencies = $accounts->keys();

        foreach ($accounts as $currency => $accountsByCurrency) {
            foreach ($accountsByCurrency as $account) {
                $account = $account->makeHidden("currency_id");
            }
        }

        return response()->json(compact("accounts", "availableCurrencies"));
    }

    public function show(Transfer $transfer)
    {
        $this->authorize("view", $transfer);

        $data = [
            "id" => $transfer->id,
            "date" => $transfer->date,
            "source" => [
                "value" => $transfer->source_value * 1,
                "account_id" => $transfer->source_account_id,
                "currency_id" => $transfer->sourceAccount->currency_id
            ],
            "target" => [
                "value" => $transfer->target_value * 1,
                "account_id" => $transfer->target_account_id,
                "currency_id" => $transfer->targetAccount->currency_id
            ]
        ];

        $accounts = auth()->user()->accounts()
            ->select("id", "icon", "name", "currency_id")
            ->orderBy("name")
            ->get()
            ->groupBy("currency_id");

        $availableCurrencies = $accounts->keys();

        foreach ($accounts as $currency => $accountsByCurrency) {
            foreach ($accountsByCurrency as $account) {
                $account = $account->makeHidden("currency_id");
            }
        }

        return response()->json(compact("data", "accounts", "availableCurrencies"));
    }

    public function update(Transfer $transfer)
    {
        $data = request()->validate([
            "date" => ["required", "date", new CorrectTransferDate],

            "source" => ["required", "array"],
            "source.value" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "source.account_id" => ["required", "integer", "different:target.account_id", new ValidTransferAccount],

            "target" => ["required", "array"],
            "target.value" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "target.account_id" => ["required", "integer", "different:source.account_id", new ValidTransferAccount],
        ]);

        $transfer->update([
            "date" => $data["date"],
            "source_value" => $data["source"]["value"],
            "source_account_id" => $data["source"]["account_id"],
            "target_value" => $data["target"]["value"],
            "target_account_id" => $data["target"]["account_id"],
        ]);

        return response("");
    }

    public function destroy(Transfer $transfer)
    {
        $transfer->delete();

        return response("");
    }

    public function store()
    {
        $data = request()->validate([
            "date" => ["required", "date", new CorrectTransferDate],

            "source" => ["required", "array"],
            "source.value" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "source.account_id" => ["required", "integer", "different:target.account_id", new ValidTransferAccount],

            "target" => ["required", "array"],
            "target.value" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "target.account_id" => ["required", "integer", "different:source.account_id", new ValidTransferAccount],
        ]);

        if (auth()->user()->extensionCodes->contains("cashan")) {
            if (request("source.cash")) {
                $sourceCash = request()->validate([
                    "source.cash" => ["required", "array"],
                    "source.cash.*.id" => ["required", "integer", new CorrectTransferCashCurrency],
                    "source.cash.*.amount" => ["required", "integer", new ValidCashAmount("expenses", "source.cash")]
                ])["source"]["cash"];
            }

            if (request("target.cash")) {
                $targetCash = request()->validate([
                    "target.cash" => ["required", "array"],
                    "target.cash.*.id" => ["required", "integer", new CorrectTransferCashCurrency],
                    "target.cash.*.amount" => ["required", "integer", new ValidCashAmount("income", "target.cash")]
                ])["target"]["cash"];
            }
        }

        auth()->user()->transfers()->create([
            "date" => $data["date"],
            "source_value" => $data["source"]["value"],
            "source_account_id" => $data["source"]["account_id"],
            "target_value" => $data["target"]["value"],
            "target_account_id" => $data["target"]["account_id"],
        ]);

        if (isset($sourceCash)) {
            foreach ($sourceCash as $item) {
                $cashAmount = auth()->user()->cash()->find($item["id"])->pivot->amount - $item["amount"];

                if ($cashAmount) {
                    auth()->user()->cash()->updateExistingPivot(
                        $item["id"],
                        ["amount" => $cashAmount]
                    );
                } else {
                    auth()->user()->cash()->detach($item["id"]);
                }
            }
        }

        if (isset($targetCash)) {
            foreach ($targetCash as $item) {
                $attachedCash = auth()->user()->cash()->find($item["id"]);

                if ($attachedCash) {
                    auth()->user()->cash()->updateExistingPivot(
                        $item["id"],
                        ["amount" => $attachedCash->pivot->amount + $item["amount"]]
                    );
                } else {
                    auth()->user()->cash()->attach(
                        $item["id"],
                        ["amount" => $item["amount"]]
                    );
                }
            }
        }

        return response("");
    }
}
