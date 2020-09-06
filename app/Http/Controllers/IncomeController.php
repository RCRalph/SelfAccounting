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

    public function show(Income $income)
    {
        $this->authorize("view", $income);

        $viewType = "income";
        $darkmode = auth()->user()->darkmode;
        $id = $income->id;

        return view('income-outcome.show', compact('viewType', 'darkmode', 'id'));
    }

    public function createOne()
    {
        $viewType = "income";
        $darkmode = auth()->user()->darkmode;
        return view("income-outcome.create.one", compact("viewType", "darkmode"));
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
            [
                "user_id" => auth()->user()->id,
                "category_id" => $data["category_id"] == "null" ? null : $data["category_id"],
                "mean_id" => $data["mean_id"] == "null" ? null : $data["mean_id"]
            ]
        ));

        return redirect("/income");
    }
}
