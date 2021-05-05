<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

use App\Bundle;

class BundlesController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function toggle(Bundle $bundle) // Change bundle between enabled / disabled
    {
        $this->authorize("hasBundle", $bundle);
        $pivot = auth()->user()->bundles
            ->where("id", $bundle->id)
            ->first()->pivot;

        $pivot->update(["enabled" => !$pivot->enabled]);

        $id = auth()->user()->id;
        Cache::forget("page-render-data-$id");

        return response("Success", 200);
    }

    public function togglePremium(Bundle $bundle) // Add or remove bundle from premium
    {
        $this->authorize("isPremium", auth()->user());
        auth()->user()->premiumBundles()->toggle($bundle);

        $id = auth()->user()->id;
        Cache::forget("page-render-data-$id");

        return response("Success", 200);
    }
}
