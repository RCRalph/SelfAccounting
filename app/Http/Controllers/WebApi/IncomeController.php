<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Currency;
use App\Income;

class IncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function start()
    {
        $currencies = Currency::all();
        return response()->json(compact('currencies'));
    }

    public function getIncome()
    {
        $currency_id = request()->validate([
            "currency_id" => ["required", "exists:currencies,id"]
        ])["currency_id"];

        $data = Income::where([
            "user_id" => auth()->user()->id,
            "currency_id" => $currency_id
        ])->paginate(20);

        return response()->json(compact("data"));
    }
}
