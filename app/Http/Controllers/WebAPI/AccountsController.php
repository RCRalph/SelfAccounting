<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Currency;
use App\Models\MeanOfPayment;

use App\Rules\Common\DateBeforeOrEqualField;
use App\Rules\Settings\CorrectFirstEntryDate;

class AccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function index(Currency $currency)
    {
        $data = auth()->user()->meansOfPayment()
            ->select("id", "name", "income_mean", "outcome_mean", "show_on_charts", "count_to_summary", "first_entry_date", "first_entry_amount")
            ->where("currency_id", $currency->id)
            ->orderBy("name")
            ->get();

        return response()->json($data);
    }

    public function create(Currency $currency)
    {
        $data = request()->validate([
            "name" => ["required", "string", "max:32"],
            "income_mean" => ["required", "boolean"],
            "outcome_mean" => ["required", "boolean"],
            "show_on_charts" => ["required", "boolean"],
            "count_to_summary" => ["required", "boolean"],
            "first_entry_date" => ["required", "date", "after_or_equal:1970-01-01"],
            "first_entry_amount" => ["required", "numeric", "min:-1e11", "max:1e11", "not_in:-1e11,1e11"]
        ]);

        auth()->user()->meansOfPayment()->create([ ...$data, "currency_id" => $currency->id ]);

        return response("");
    }

    public function show(MeanOfPayment $mean)
    {
        $this->authorize("view", $mean);

        $mean = $mean->makeHidden(["user_id", "currency_id", "created_at", "updated_at"]);

        $minDate = auth()->user()->income()
            ->select("date")
            ->where("mean_id", $mean->id)
            ->union(auth()->user()->outcome()
                ->select("date")
                ->where("mean_id", $mean->id)
            )
            ->orderBy("date")
            ->first();

        $mean->max_date = $minDate->date ?? null;

        return response()->json([ "data" => $mean ]);
    }

    public function update(MeanOfPayment $mean)
    {
        $this->authorize("update", $mean);

        $data = request()->validate([
            "name" => ["required", "string", "max:32"],
            "income_mean" => ["required", "boolean"],
            "outcome_mean" => ["required", "boolean"],
            "show_on_charts" => ["required", "boolean"],
            "count_to_summary" => ["required", "boolean"],
            "first_entry_date" => ["required", "date", "after_or_equal:1970-01-01", new CorrectFirstEntryDate($mean)],
            "first_entry_amount" => ["required", "numeric", "min:-1e11", "max:1e11", "not_in:-1e11,1e11"]
        ]);

        $mean->update($data);

        return response("");
    }

    public function delete(MeanOfPayment $mean)
    {
        $this->authorize("delete", $mean);

        $mean->delete();

        return response("");
    }
}
