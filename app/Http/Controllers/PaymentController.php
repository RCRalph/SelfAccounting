<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $data = request()->validate([
            "bundle" => ["nullable", "integer", "exists:bundles,id"]
        ]);
        $id = array_key_exists("bundle", $data) ? $data["bundle"] : 0;
        $userId = auth()->user()->id;

        $pageData = $this->getDataForPageRender();
        return view("payment.index", compact("pageData", "id", "userId"));
    }
}
