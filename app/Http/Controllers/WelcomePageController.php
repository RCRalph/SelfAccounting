<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomePageController extends Controller
{
    public function index()
    {
        // Redirect if authenticated
        if (Auth::check()) {
            return redirect()->route("summary");
        }

        return view("welcome");
    }
}
