<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $viewType = "income";

        return view('income-outcome.index', compact('viewType'));
    }

    public function show($id)
    {
        $viewType = "income";

        return view('income-outcome.show', compact('viewType'));
    }

    public function createOne()
    {
        $viewType = "income";

        return view('income-outcome.create.one', compact('viewType'));
    }
}
