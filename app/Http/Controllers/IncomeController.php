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

        return view('income-outcome.create.one', compact('viewType', 'darkmode'));
    }
}
