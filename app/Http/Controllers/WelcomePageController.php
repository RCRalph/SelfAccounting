<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomePageController extends Controller
{
    public function index()
    {
        $darkmode = Auth::check() ? auth()->user()->darkmode : false;

        return view('welcome', compact('darkmode'));
    }
}
