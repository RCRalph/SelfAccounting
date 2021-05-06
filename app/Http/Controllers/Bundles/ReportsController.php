<?php

namespace App\Http\Controllers\Bundles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "bundle:report"]);
    }

    public function index()
    {
        $pageData = $this->getDataForPageRender();

        return view("bundles.reports.index", compact("pageData"));
    }

    public function create()
    {
        $pageData = $this->getDataForPageRender();

        return view("bundles.reports.create", compact("pageData"));
    }
}
