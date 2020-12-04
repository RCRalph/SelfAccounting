<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Currency;
use App\Outcome;

use App\Rules\CorrectDateIO_One;
use App\Rules\ValidCategoryMean;

class OutcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $viewType = "outcome";
        $pageData = $this->getDataForPageRender();

        return view("income-outcome.index", compact("viewType", "pageData"));
    }

    public function show(Outcome $outcome)
    {
        $this->authorize("view", $outcome);

        $viewType = "outcome";
        $pageData = $this->getDataForPageRender();
        $id = $outcome->id;

        return view("income-outcome.show", compact("viewType", "pageData", "id"));
    }

    public function createOne()
    {
        $viewType = "outcome";
        $pageData = $this->getDataForPageRender();
        return view("income-outcome.create.one", compact("viewType", "pageData"));
	}
}
