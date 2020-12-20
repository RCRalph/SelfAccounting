<?php

namespace App\Http\Controllers\Admin;

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

    public function list()
    {
        $pageData = $this->getDataForPageRender();

        return view("admin.bundles.list", compact("pageData"));
    }
}
