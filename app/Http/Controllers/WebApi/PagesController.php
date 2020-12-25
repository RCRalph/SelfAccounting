<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Bundle;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function payment()
    {
        $bundles = Bundle::all()->map(fn($item) => $item->only(["id", "price", "title"]));

        return response()->json(compact("bundles"));
    }
}
