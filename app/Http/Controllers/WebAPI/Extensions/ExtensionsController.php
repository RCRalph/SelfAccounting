<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Extension;

class ExtensionsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $extensions = Extension::all()->load("gallery")
            ->makeHidden(["thumbnail", "created_at", "updated_at", "icon"])
            ->toArray();

        foreach ($extensions as $key => $item) {
            $extensions[$key]["gallery"] = array_column($item["gallery"], "image_link");
        }

        $ownedExtensions = auth()->user()->extensions()
            ->select("code", "enabled")->get()
            ->makeHidden(["thumbnail_link", "pivot"])
            ->mapWithKeys(fn ($item) => [$item["code"] => $item["enabled"]]);

        $premiumExtensions = auth()->user()->premiumExtensions->pluck("code");

        $isPremium = in_array(strtolower(auth()->user()->account_type), ["admin", "premium"]);

        return response()->json(compact("extensions", "ownedExtensions", "premiumExtensions", "isPremium"));
    }

    public function toggle()
    {
        $this->authorize("toggle", request()->extension);

        $currentlyEnabled = auth()->user()->extensions()
            ->find(request()->extension)->pivot->enabled;

        auth()->user()->extensions()
            ->updateExistingPivot(request()->extension, ["enabled" => !$currentlyEnabled]);

        return response()->json(["extensions" => auth()->user()->refresh()->extensionCodes]);
    }

    public function togglePremium()
    {
        $this->authorize("togglePremium", request()->extension);

        auth()->user()->premiumExtensions()->toggle(request()->extension);

        return response()->json(["extensions" => auth()->user()->refresh()->extensionCodes]);
    }
}
