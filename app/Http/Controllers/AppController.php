<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function app()
    {
        return view("app");
    }

    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('app');
        }

        return view('index');
    }
}
