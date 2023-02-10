<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

use App\Rules\Exchange\CorrectDateIncomeExpencesExchange;
use App\Rules\Exchange\ValidCategoryOrAccountExchange;
use App\Rules\Extensions\Cash\CashValidAmount;
use App\Rules\Extensions\Cash\CorrectCashCurrency;

use App\Models\Currency;

class ExchangeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function show()
    {
        $categories = auth()->user()->categories()
            ->select("id", "name", "used_in_income", "used_in_expences", "currency_id")
            ->orderBy("name")
            ->get()
            ->groupBy("currency_id");

        foreach ($categories as $currency => $categoriesByCurrency) {
            foreach ($categoriesByCurrency as $category) {
                $category = $category->makeHidden("currency_id");
            }
        }

        foreach ($this->getCurrencies() as $currency) {
            if ($categories->has($currency->id)) {
                $categories[$currency->id]->prepend([
                    "id" => null,
                    "name" => "N/A",
                    "used_in_income" => true,
                    "used_in_expences" => true
                ]);
            }
            else {
                $categories[$currency->id] = collect([[
                    "id" => null,
                    "name" => "N/A",
                    "used_in_income" => true,
                    "used_in_expences" => true
                ]]);
            }
        }

        $accounts = auth()->user()->accounts()
            ->select("id", "name", "used_in_income", "used_in_expences", "currency_id")
            ->orderBy("name")
            ->get()
            ->groupBy("currency_id");

        $availableCurrencies = $accounts->keys();

        foreach ($accounts as $currency => $accountsByCurrency) {
            foreach ($accountsByCurrency as $account) {
                $account = $account->makeHidden("currency_id");
            }
        }

        foreach ($this->getCurrencies() as $currency) {
            if (!$accounts->has($currency->id)) {
                $accounts[$currency->id] = collect([]);
            }
        }

        $titles = $this->getTitles();

        return response()->json(compact("categories", "accounts", "availableCurrencies", "titles"));
    }

    public function store()
    {
        $data = request()->validate([
            "from.date" => ["required", "date", new CorrectDateIncomeExpencesExchange("from")],
            "from.title" => ["required", "string", "max:64"],
            "from.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "from.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "from.currency_id" => ["required", "integer", "exists:currencies,id"],
            "from.category_id" => ["present", "nullable", "integer", new ValidCategoryOrAccountExchange("from")],
            "from.account_id" => ["required", "integer", "different:to.account_id", new ValidCategoryOrAccountExchange("from")],

            "to.date" => ["required", "date", new CorrectDateIncomeExpencesExchange("to")],
            "to.title" => ["required", "string", "max:64"],
            "to.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "to.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "to.currency_id" => ["required", "integer", "exists:currencies,id"],
            "to.category_id" => ["present", "nullable", "integer", new ValidCategoryOrAccountExchange("to")],
            "to.account_id" => ["required", "integer", "different:from.account_id", new ValidCategoryOrAccountExchange("to")],
        ]);

        if (auth()->user()->extensionCodes->contains("cashan")) {
            if (request("fromCash")) {
                $fromCash = request()->validate([
                    "fromCash" => ["required", "array"],
                    "fromCash.*.id" => ["required", "integer", new CorrectCashCurrency(Currency::find($data["from"]["currency_id"]))],
                    "fromCash.*.amount" => ["required", "integer", "min:0", "max:1e7", "not_in:1e7", new CashValidAmount("expences", "fromCash")]
                ])["fromCash"];
            }

            if (request("toCash")) {
                $toCash = request()->validate([
                    "toCash" => ["required", "array"],
                    "toCash.*.id" => ["required", "integer", new CorrectCashCurrency(Currency::find($data["to"]["currency_id"]))],
                    "toCash.*.amount" => ["required", "integer", "min:0", "max:1e7", "not_in:1e7", new CashValidAmount("income", "toCash")]
                ])["toCash"];
            }
        }

        auth()->user()->expences()->create($data["from"]);
        auth()->user()->income()->create($data["to"]);

        if (isset($fromCash)) {
            foreach ($fromCash as $item) {
                $cashAmount = auth()->user()->cash()->find($item["id"])->pivot->amount - $item["amount"];

                if ($cashAmount) {
                    auth()->user()->cash()->updateExistingPivot(
                        $item["id"],
                        ["amount" => $cashAmount]
                    );
                }
                else {
                    auth()->user()->cash()->detach($item["id"]);
                }
            }
        }

        if (isset($toCash)) {
            foreach ($toCash as $item) {
                $attachedCash = auth()->user()->cash()->find($item["id"]);

                if ($attachedCash) {
                    auth()->user()->cash()->updateExistingPivot(
                        $item["id"],
                        ["amount" => $attachedCash->pivot->amount + $item["amount"]]
                    );
                }
                else {
                    auth()->user()->cash()->attach(
                        $item["id"],
                        [ "amount" => $item["amount"] ]
                    );
                }
            }
        }

        return response("");
    }
}
