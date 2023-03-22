<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\Currency;
use App\Models\Account;

use App\Rules\Accounts\CorrectStartDate;

class AccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index(Currency $currency)
    {
        $data = auth()->user()->accounts()
            ->select("id", "icon", "name", "used_in_income", "used_in_expenses", "show_on_charts", "count_to_summary", "start_date", "start_balance")
            ->where("currency_id", $currency->id)
            ->orderBy("name")
            ->get();

        return response()->json($data);
    }

    public function create(Currency $currency)
    {
        $data = request()->validate([
            "icon" => ["present", "nullable", "string", "max:64"],
            "name" => ["required", "string", "max:32"],
            "used_in_income" => ["required", "boolean"],
            "used_in_expenses" => ["required", "boolean"],
            "show_on_charts" => ["required", "boolean"],
            "count_to_summary" => ["required", "boolean"],
            "start_date" => ["required", "date", "after_or_equal:1970-01-01"],
            "start_balance" => ["required", "numeric", "min:-1e11", "max:1e11", "not_in:-1e11,1e11"]
        ]);

        auth()->user()->accounts()->create([ ...$data, "currency_id" => $currency->id ]);

        return response("");
    }

    public function show(Account $account)
    {
        $this->authorize("view", $account);

        $account = $account->makeHidden(["user_id", "currency_id", "created_at", "updated_at"]);

        $minDate = auth()->user()->income()
            ->select("date")
            ->where("account_id", $account->id)
            ->union(auth()->user()->expenses()
                ->select("date")
                ->where("account_id", $account->id)
            )
            ->orderBy("date")
            ->first();

        $account->max_date = $minDate->date ?? null;

        return response()->json([ "data" => $account ]);
    }

    public function update(Account $account)
    {
        $this->authorize("update", $account);

        $data = request()->validate([
            "icon" => ["present", "nullable", "string", "max:64"],
            "name" => ["required", "string", "max:32"],
            "used_in_income" => ["required", "boolean"],
            "used_in_expenses" => ["required", "boolean"],
            "show_on_charts" => ["required", "boolean"],
            "count_to_summary" => ["required", "boolean"],
            "start_date" => ["required", "date", "after_or_equal:1970-01-01", new CorrectStartDate($account)],
            "start_balance" => ["required", "numeric", "min:-1e11", "max:1e11", "not_in:-1e11,1e11"]
        ]);

        $account->update($data);

        return response("");
    }

    public function delete(Account $account)
    {
        $this->authorize("delete", $account);

        $account->delete();

        return response("");
    }

    public function icons()
    {
        $icons = Account::select("icon", DB::raw("COUNT(icon) AS count"))
            ->whereNotNull("icon")
            ->groupBy("icon")
            ->orderBy("count", "DESC")
            ->orderBy("icon", "DESC")
            ->limit(16)
            ->pluck("icon");

        foreach (Account::$ICONS as $icon) {
            if ($icons->count() == 16) {
                break;
            } else if (!$icons->contains($icon)) {
                $icons->push($icon);
            }
        }

        return response()->json(compact("icons"));
    }
}
