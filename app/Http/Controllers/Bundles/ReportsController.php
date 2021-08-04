<?php

namespace App\Http\Controllers\Bundles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Report;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "bundle:report"]);
    }

    public function index()
    {
        $pageData = $this->getDataForPageRender();
        $tutorial = $this->getTutorial("report-management.md");

        return view("bundles.reports.index", compact("pageData", "tutorial"));
    }

    public function create()
    {
        $pageData = $this->getDataForPageRender();

        return view("bundles.reports.create", compact("pageData"));
    }

    public function edit(Report $report)
    {
        $this->authorize("update", $report);

        $pageData = $this->getDataForPageRender();
        $id = $report->id;

        return view("bundles.reports.edit", compact("pageData", "id"));
    }

    public function show(Report $report)
    {
        $this->authorize("view", $report);

        $pageData = $this->getDataForPageRender();

        return view("bundles.reports.show", compact("pageData", "report"));
    }
}
