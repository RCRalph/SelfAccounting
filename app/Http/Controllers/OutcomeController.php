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

        return view('income-outcome.index', compact('viewType'));
    }

    public function show($id)
    {
        $viewType = "outcome";

        return view('income-outcome.show', compact('viewType'));
    }

    public function createOne()
    {
        $viewType = "outcome";

        return view('income-outcome.create.one', compact('viewType'));
    }
}
