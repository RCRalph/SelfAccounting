<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

use App\Rules\Exchange\CorrectDateIOExchange;
use App\Rules\Exchange\ValidCategoryOrMeanExchange;
use App\Rules\Extensions\Cash\CashValidAmount;
use App\Rules\Extensions\Cash\CorrectCashCurrency;

use App\Currency;

class ExchangeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function show()
    {
        $categories = auth()->user()->categories()
            ->select("id", "name", "income_category", "outcome_category", "currency_id")
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
                    "income_category" => true,
                    "outcome_category" => true
                ]);
            }
            else {
                $categories[$currency->id] = collect([[
                    "id" => null,
                    "name" => "N/A",
                    "income_category" => true,
                    "outcome_category" => true
                ]]);
            }
        }

        $means = auth()->user()->meansOfPayment()
            ->select("id", "name", "income_mean", "outcome_mean", "currency_id")
            ->orderBy("name")
            ->get()
            ->groupBy("currency_id");

        $availableCurrencies = $means->keys();

        foreach ($means as $currency => $meansByCurrency) {
            foreach ($meansByCurrency as $mean) {
                $mean = $mean->makeHidden("currency_id");
            }
        }

        foreach ($this->getCurrencies() as $currency) {
            if (!$means->has($currency->id)) {
                $means[$currency->id] = collect([]);
            }
        }

        $titles = $this->getTitles();

        return response()->json(compact("categories", "means", "availableCurrencies", "titles"));
    }

    public function store()
    {
        $data = request()->validate([
            "from.date" => ["required", "date", new CorrectDateIOExchange("from")],
            "from.title" => ["required", "string", "max:64"],
            "from.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "from.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "from.currency_id" => ["required", "integer", "exists:currencies,id"],
            "from.category_id" => ["present", "nullable", "integer", new ValidCategoryOrMeanExchange("from")],
            "from.mean_id" => ["required", "integer", "different:to.mean_id", new ValidCategoryOrMeanExchange("from")],

            "to.date" => ["required", "date", new CorrectDateIOExchange("to")],
            "to.title" => ["required", "string", "max:64"],
            "to.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "to.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "to.currency_id" => ["required", "integer", "exists:currencies,id"],
            "to.category_id" => ["present", "nullable", "integer", new ValidCategoryOrMeanExchange("to")],
            "to.mean_id" => ["required", "integer", "different:from.mean_id", new ValidCategoryOrMeanExchange("to")],
        ]);

        auth()->user()->outcome()->create($data["from"]);
        auth()->user()->income()->create($data["to"]);

        if (auth()->user()->extensionCodes->contains("cashan")) {
            if (request("fromCash")) {
                $cash = request()->validate([
                    "fromCash" => ["required", "array"],
                    "fromCash.*.id" => ["required", "integer", new CorrectCashCurrency(Currency::find($data["from"]["currency_id"]))],
                    "fromCash.*.amount" => ["required", "integer", "min:0", "max:1e7", "not_in:1e7", new CashValidAmount("outcome", "fromCash")]
                ])["fromCash"];

                foreach ($cash as $item) {
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

            if (request("toCash")) {
                $cash = request()->validate([
                    "toCash" => ["required", "array"],
                    "toCash.*.id" => ["required", "integer", new CorrectCashCurrency(Currency::find($data["to"]["currency_id"]))],
                    "toCash.*.amount" => ["required", "integer", "min:0", "max:1e7", "not_in:1e7", new CashValidAmount("income", "toCash")]
                ])["toCash"];

                foreach ($cash as $item) {
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
        }

        return response("");
    }
}
