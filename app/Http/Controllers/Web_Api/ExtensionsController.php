<?php

namespace App\Http\Controllers\Web_Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

use App\extension;

class ExtensionsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function toggle(Extension $extension) // Change extension between enabled / disabled
    {
        $this->authorize("hasExtension", $extension);
        $pivot = auth()->user()->extensions
            ->where("id", $extension->id)
            ->first()->pivot;

        $pivot->update(["enabled" => !$pivot->enabled]);

        $id = auth()->user()->id;
        Cache::forget("page-render-data-$id");

        return response("Success", 200);
    }

    public function togglePremium(Extension $extension) // Add or remove extension from premium
    {
        $this->authorize("isPremium", auth()->user());
        auth()->user()->premiumExtensions()->toggle($extension);

        $id = auth()->user()->id;
        Cache::forget("page-render-data-$id");

        return response("Success", 200);
    }
}
