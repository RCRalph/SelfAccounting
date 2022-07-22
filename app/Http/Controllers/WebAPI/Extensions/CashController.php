<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Currency;

class CashController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "extension:cashan"]);
    }

    public function index(Currency $currency)
    {
        $cash = $currency->cash()->select("id", "value")
            ->orderBy("value", "DESC")->get()
            ->mapWithKeys(fn ($item) => [$item["id"] => $item["value"] * 1]);

        $cashMean = auth()->user()->cashMeans()
            ->where("currency_id", $currency->id)
            ->pluck("mean_of_payments.id")
            ->toArray();

        $cashMean = $cashMean ? $cashMean[0] : null;

        $ownedCash = auth()->user()->cash()->where("currency_id", $currency->id)->get()
            ->mapWithKeys(fn ($item) => [$item->id => $item->pivot->amount]);

        return response()->json(compact("cash", "cashMean", "ownedCash"));
    }
}
