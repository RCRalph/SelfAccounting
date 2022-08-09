<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Currency;

use App\Rules\Extensions\Cash\CorrectCashMean;
use App\Rules\Extensions\Cash\CorrectCashCurrency;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "extension:report"]);
    }

    public function index(Currency $currency)
    {
        return response()->json(compact());
    }
}
