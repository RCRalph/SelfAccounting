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
            ->makeHidden(["id", "thumbnail", "created_at", "updated_at", "icon", "directory"]);

        $ownedExtensions = auth()->user()->extensions()
            ->select("code", "enabled")->get()
            ->makeHidden(["thumbnail_link", "pivot"])
            ->mapWithKeys(fn ($item) => [$item["code"] => $item["enabled"]]);

        $premiumExtensions = auth()->user()->premiumExtensions->pluck("code");

        $isPremium = in_array(strtolower(auth()->user()->account_type), ["admin", "premium"]);

        return response()->json(compact("extensions", "ownedExtensions", "premiumExtensions", "isPremium"));
    }
}
