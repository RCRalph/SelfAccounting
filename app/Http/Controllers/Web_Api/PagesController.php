<?php

namespace App\Http\Controllers\Web_Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Extension;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function payment()
    {
        $extensions = Extension::all()->map(fn($item) => $item->only(["id", "price", "title"]));

        return response()->json(compact("extensions"));
    }
}
