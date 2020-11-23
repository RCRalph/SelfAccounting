<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomePageController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route("summary");
        }

        $darkmode = false;
        return view('welcome', compact('darkmode'));
    }
}
