<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Currency;
use App\Income;

use App\Rules\CorrectDateIO_One;
use App\Rules\ValidCategoryMean;

class IncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $viewType = "income";
        $darkmode = auth()->user()->darkmode;

        return view('income-outcome.index', compact('viewType', 'darkmode'));
    }

    public function show($id)
    {
        $viewType = "income";
        $darkmode = auth()->user()->darkmode;

        return view('income-outcome.show', compact('viewType', 'darkmode'));
    }

    public function createOne()
    {
        $viewType = "income";
        $darkmode = auth()->user()->darkmode;
        $currencies = Currency::all();
        $categories = auth()->user()->categories->where("income_category", true);
        $means = auth()->user()->meansOfPayment->where("income_mean", true);

        $income = auth()->user()->income;

        $lastCurrency = $income->concat(auth()->user()->outcome)->sortBy("date")->last();
        $lastCurrency = $lastCurrency == null ? 1 : $lastCurrency->currency_id;

        $income = $income->sortBy("date")->last();
        $lastMean = $income == null ? 0 : $income->mean_id;
        $lastCategory = $income == null ? 0 : $income->category_id;

        return view('income-outcome.create.one', compact(
            'viewType', 'darkmode', 'currencies', 'categories', 'means', 'lastCurrency', 'lastCategory', 'lastMean'
        ));
	}

	public function storeOne()
	{
		$data = request()->validate([
            "date" => ["required", "date", new CorrectDateIO_One],
            "title" => ["required", "string", "max:64"],
            "amount" => ["required", "numeric"],
            "price" => ["required", "numeric"],
            "currency_id" => ["required", "exists:currencies,id"],
            "category_id" => ["present", "nullable", new ValidCategoryMean],
            "mean_id" => ["present", "nullable", new ValidCategoryMean]
        ]);

        Income::create(array_merge(
            $data,
            ["user_id" => auth()->user()->id]
        ));

        return redirect("/income");
	}
}
