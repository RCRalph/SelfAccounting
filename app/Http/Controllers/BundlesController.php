<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Extension;
use App\ExtensionImage;

class BundlesController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $pageData = $this->getDataForPageRender();
        $tutorial = $this->getTutorial("bundles.md");
        $bundles = Extension::all()->sortBy("created_at")->map(function ($item) {
            $item->thumbnail = $this->getLocalImageLink(
                $this->PUBLIC_DIRECTORIES[0],
                $item->thumbnail
            );
            return $item;
        });

        return view("bundles.index", compact("pageData", "bundles", "tutorial"));
    }

    public function show(Extension $bundle) {
        $pageData = $this->getDataForPageRender();

        $hasBundle = auth()->user()->extensions->contains($bundle);
        $hasBundlePremium = auth()->user()->premiumExtensions->contains($bundle);

        $bundleEnabled = false;
        if ($hasBundle) {
            $bundleEnabled = auth()->user()->extensions
                ->where("id", $bundle->id)
                ->first()->pivot->enabled;
        }

        $gallery = $bundle->gallery->map(fn ($item) => $this->getLocalImageLink($this->PUBLIC_DIRECTORIES[1], $item->image));
        $isPremium = $this->checkPremium(auth()->user());

        return view("bundles.show", compact("pageData", "isPremium", "bundle", "gallery", "hasBundle", "bundleEnabled", "hasBundlePremium"));
    }
}
