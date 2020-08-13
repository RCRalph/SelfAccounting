<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OutcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $viewType = "outcome";
        $darkmode = auth()->user()->darkmode;

        return view('income-outcome.index', compact('viewType', 'darkmode'));
    }

    public function show($id)
    {
        $viewType = "outcome";
        $darkmode = auth()->user()->darkmode;

        return view('income-outcome.show', compact('viewType', 'darkmode'));
    }

    public function createOne()
    {
        $viewType = "outcome";
        $darkmode = auth()->user()->darkmode;

        return view('income-outcome.create.one', compact('viewType', 'darkmode'));
    }
}
