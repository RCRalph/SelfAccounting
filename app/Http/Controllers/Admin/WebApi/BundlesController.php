<?php

namespace App\Http\Controllers\Admin\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Admin;

use App\User;
use App\Bundle;

class BundlesController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", Admin::class]);
    }

    public function bundles() // Get list of bundles
    {
        $users = Bundle::where("id", ">", "0")
            ->select("id", "title")
            ->orderBy("id")
            ->paginate(20);

        return response()->json(compact("users"));
    }

    private function getBundleTitles($toExclude = "")
    {
        $titles = Bundle::all()->map(function($item) {
            return $item->only("title");
        })->toArray();

        foreach ($titles as $key => $value) {
            $titles[$key] = strtolower($value["title"]);
        }

        $titles = array_values(array_filter(
            $titles,
            fn ($item) => $item != strtolower($toExclude)
        ));

        return $titles;
    }

    public function getCreateData()
    {
        $titles = $this->getBundleTitles();

        return response()->json(compact("titles"));
    }

    public function details(Bundle $bundle)
    {
        $titles = $this->getBundleTitles($bundle->title);
        $bundle = collect($bundle)->forget(["created_at", "updated_at", "thumbnail"]);

        return response()->json(compact("titles", "bundle"));
    }
}
