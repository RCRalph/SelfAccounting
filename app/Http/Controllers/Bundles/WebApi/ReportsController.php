<?php

namespace App\Http\Controllers\Bundles\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "bundle:report"]);
    }

    public function userReports()
    {
        $reports = auth()->user()->reports()
            ->select("id", "title")
            ->orderBy("id")
            ->paginate(10);

        return response()->json(compact("reports"));
    }

    public function sharedReports()
    {
        $reports = auth()->user()->sharedReports()
            ->select("id", "title")
            ->orderBy("id")
            ->paginate(10);

        return response()->json(compact("reports"));
    }
}
