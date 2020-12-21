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

    public function getCreateData()
    {
        $titles = Bundle::all()->map(function($item) {
            return $item->only("title");
        });

        foreach ($titles as $key => $value) {
            $titles[$key] = strtolower($value["title"]);
        }

        return response()->json(compact("titles"));
    }
}
