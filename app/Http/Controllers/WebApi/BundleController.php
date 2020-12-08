<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Bundle;

class BundleController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function toggle()
    {
        $data = request()->validate([
            "id" => ["required", "exists:bundles"]
        ]);

        $this->authorize("hasBundle", Bundle::find($data["id"]));

        $pivot = auth()->user()->bundles
            ->where("id", $data["id"])
            ->first()->pivot;

        $pivot->update(["enabled" => !$pivot->enabled]);

        return response("", 200);
    }

    public function togglePremium()
    {
        $this->authorize("isPremium", auth()->user());
        $data = request()->validate([
            "id" => ["required", "exists:bundles"]
        ]);

        auth()->user()->premium_bundles()->toggle(Bundle::find($data["id"]));

        return response("", 200);
    }
}
