<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Currency;
use App\Income;
use App\Outcome;

use App\Rules\CorrectDateIO_One;
use App\Rules\ValidCategoryMean;
use App\Http\Middleware\IncomeOutcome;

class IncomeOutcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", IncomeOutcome::class]);
    }

    public function index($viewType)
    {
        $pageData = $this->getDataForPageRender();

        return view("income-outcome.index", compact("viewType", "pageData"));
    }

    public function edit($viewType, $incomeOutcome)
    {
        if (!intval($incomeOutcome)) {
            return abort(404);
        }

        $incomeOutcome = $viewType == "income" ?
            Income::findOrFail($incomeOutcome) :
            Outcome::findOrFail($incomeOutcome);

        $this->authorize("viewIncomeOutcome", $incomeOutcome);

        $pageData = $this->getDataForPageRender();
        $id = $incomeOutcome->id;

        return view("income-outcome.edit", compact("viewType", "pageData", "id"));
    }

    public function createOne($viewType)
    {
        $pageData = $this->getDataForPageRender();
        return view("income-outcome.create-one", compact("viewType", "pageData"));
    }

    public function createMultiple($viewType)
    {
        $pageData = $this->getDataForPageRender();
        return view("income-outcome.create-multiple", compact("viewType", "pageData"));
    }
}
