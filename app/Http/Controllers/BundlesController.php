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
            $item->thumbnail = $this->getLocalImageLink(
                $this->PUBLIC_DIRECTORIES[0],
                $item->thumbnail
            );
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

        $gallery = $bundle->gallery->map(fn ($item) => $this->getLocalImageLink($this->PUBLIC_DIRECTORIES[1], $item->image));
        $isPremium = $this->checkPremium(auth()->user());

        return view("bundles.show", compact("pageData", "isPremium", "bundle", "gallery", "hasBundle", "bundleEnabled", "hasBundlePremium"));
    }
}
