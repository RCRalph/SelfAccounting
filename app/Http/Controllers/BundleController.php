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

    public function show(Bundle $bundle) {
        $pageData = $this->getDataForPageRender();

        $images = $bundle->images->map(function($item) {
            return "/img/bundles/galleries/" . $item->image;
        });

        $isPremium = $this->checkPremium(auth()->user());

        return view("bundles.show", compact("pageData", "isPremium", "bundle", "images"));
    }
}
