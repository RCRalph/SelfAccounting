<?php

namespace App\Http\Controllers\Bundles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CashController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "bundle:cashan"]);
    }

    public function index()
    {
        $pageData = $this->getDataForPageRender();

        return view("bundles.cash.index", compact("pageData"));
    }
}
