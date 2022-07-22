<?php

namespace App\Http\Controllers\Bundles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "extension:backup"]);
    }

    public function index()
    {
        $pageData = $this->getDataForPageRender();
        $tutorial = $this->getTutorial("backup-data.md");

        return view("bundles.backup.index", compact("pageData", "tutorial"));
    }
}
