<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Bundle;
use App\BundleImage;

class BundlesController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $pageData = $this->getDataForPageRender();
        $bundles = Bundle::all()->sortBy("created_at")->map(function($item) {
            $endpoint = env('IBM_COS_ENDPOINT');
            $bucket = env('IBM_COS_BUCKET');
            $item->thumbnail = "$endpoint/$bucket/bundles/thumbnails/$item->thumbnail";
            return $item;
        });

        return view("bundles.index", compact("pageData", "bundles"));
    }

    public function show(Bundle $bundle) {
        $pageData = $this->getDataForPageRender();

        $hasBundle = auth()->user()->bundles->contains($bundle);
        $hasBundlePremium = auth()->user()->premium_bundles->contains($bundle);

        $bundleEnabled = false;
        if ($hasBundle) {
            $bundleEnabled = auth()->user()->bundles
                ->where("id", $bundle->id)
                ->first()->pivot->enabled;
        }

        $gallery = $bundle->gallery->map(function($item) {
            $endpoint = env('IBM_COS_ENDPOINT');
            $bucket = env('IBM_COS_BUCKET');
            return "$endpoint/$bucket/bundles/gallery/$item->image";
        });

        $isPremium = $this->checkPremium(auth()->user());

        return view("bundles.show", compact("pageData", "isPremium", "bundle", "gallery", "hasBundle", "bundleEnabled", "hasBundlePremium"));
    }
}
