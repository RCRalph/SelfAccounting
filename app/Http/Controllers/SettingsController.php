<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $darkmode = auth()->user()->darkmode;
        return view('settings.index', compact('darkmode'));
    }
}
