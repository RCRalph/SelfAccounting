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
        $tutorial = $this->getTutorial("cash-handling.md");

        return view("bundles.cash.index", compact("pageData", "tutorial"));
    }
}
