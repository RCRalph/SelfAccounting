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
}
