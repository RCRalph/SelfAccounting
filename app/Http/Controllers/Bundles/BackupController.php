<?php

namespace App\Http\Controllers\Bundles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "bundle:backup"]);
    }

    public function index()
    {
        $pageData = $this->getDataForPageRender();

        return view("bundles.backup.index", compact("pageData"));
    }
}
