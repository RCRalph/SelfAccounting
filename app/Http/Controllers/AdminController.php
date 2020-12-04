<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\Admin;

use App\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", Admin::class]);
    }

    public function users()
    {
        $pageData = $this->getDataForPageRender();

        return view("admin.users", compact("pageData"));
    }
}
