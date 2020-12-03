<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $darkmode = auth()->user()->darkmode;
        return view("summary.index", compact("darkmode"));
    }
}
