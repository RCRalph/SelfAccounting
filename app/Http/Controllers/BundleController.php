<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Bundle;
use App\BundleImage;

class BundleController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $pageData = $this->getDataForPageRender();
        $bundles = Bundle::all();

        return view("bundles.index", compact("pageData", "bundles"));
    }
}
