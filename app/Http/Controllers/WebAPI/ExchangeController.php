<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

use App\Rules\CorrectDateIOExchange;
use App\Rules\ValidCategoryOrMeanExchange;

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
            "from.mean_id" => ["required", "integer", new ValidCategoryOrMeanExchange("from")],

            "to.date" => ["required", "date", new CorrectDateIOExchange("to")],
            "to.title" => ["required", "string", "max:64"],
            "to.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "to.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "to.currency_id" => ["required", "integer", "exists:currencies,id"],
            "to.category_id" => ["present", "nullable", "integer", new ValidCategoryOrMeanExchange("to")],
            "to.mean_id" => ["required", "integer", new ValidCategoryOrMeanExchange("to")],
        ]);

        auth()->user()->outcome()->create($data["from"]);
        auth()->user()->income()->create($data["to"]);

        return response("");
    }
}
