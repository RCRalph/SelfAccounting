<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Bundle;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $bundles = Bundle::all()->map(fn($item) => $item->only(["id", "price", "title"]));

        return response()->json(compact("bundles"));
    }
}
