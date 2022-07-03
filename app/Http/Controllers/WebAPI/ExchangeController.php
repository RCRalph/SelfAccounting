<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

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

        return response()->json(compact("categories", "means", "availableCurrencies"));
    }
}
