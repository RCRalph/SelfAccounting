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
        return view('outcome.index');
    }

    public function createOne()
    {
        return view('outcome.create.one');
    }
}
