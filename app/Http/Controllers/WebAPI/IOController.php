<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use App\Models\Income;
use App\Models\Outcome;
use App\Models\Currency;
use App\Models\Chart;

use App\Rules\IO\CorrectDateIO;
use App\Rules\IO\CorrectDateIOUpdate;
use App\Rules\IO\ValidCategoryOrAccount;
use App\Rules\IO\ValidCategoryOrAccountUpdate;
use App\Rules\Common\SameLengthAs;
use App\Rules\Common\DateBeforeOrEqualField;
use App\Rules\Extensions\Cash\CorrectCashCurrency;
use App\Rules\Extensions\Cash\CashValidAmount;

class IOController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function getCategoriesAndAccounts($currency)
    {
        $categories = auth()->user()->categories()
            ->select("id", "name")
            ->where("currency_id", $currency->id)
            ->where("used_in_" . request()->type, true)
            ->orderBy("name")
            ->get()
            ->prepend(["id" => null, "name" => "N/A"]);

        $accounts = auth()->user()->accounts()
            ->select("id", "name", "start_date")
            ->where("currency_id", $currency->id)
            ->where("used_in_" . request()->type, true)
            ->orderBy("name")
            ->get()
            ->prepend(["id" => null, "name" => "N/A", "start_date" => "1970-01-01"]);

        return compact("categories", "accounts");
    }

    public function show($id)
    {
        $data = $this->getTypeRelation()
            ->select("id", "date", "title", "amount", "price", "category_id", "account_id", "currency_id")
            ->where("id", $id)
            ->get()->firstOrFail();

        $data->price *= 1;
        $data->amount *= 1;

        $accountsAndCategories = $this->getCategoriesAndAccounts($data->currency);

        $titles = $this->getTitles();

        $data = collect($data);
        $data->forget("currency");

        return response()->json([ ...compact("data", "titles"), ...$accountsAndCategories ]);
    }

    public function update($id)
    {
        $toUpdate = $this->getTypeRelation()
            ->where("id", $id)
            ->get()->firstOrFail();

        $data = request()->validate([
            "date" => ["required", "date", new CorrectDateIOUpdate],
            "title" => ["required", "string", "max:64"],
            "amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "currency_id" => ["required", "integer", "exists:currencies,id"],
            "category_id" => ["present", "nullable", "integer", new ValidCategoryOrAccountUpdate],
            "account_id" => ["present", "nullable", "integer", new ValidCategoryOrAccountUpdate]
        ]);

        $toUpdate->update($data);

        return response("", 200);
    }

    public function destroy($id)
    {
        $this->getTypeRelation()
            ->where("id", $id)
            ->get()->firstOrFail()
            ->delete();

        return response("", 200);
    }

    public function index(Currency $currency)
    {
        $categories = auth()->user()->categories()
            ->where("currency_id", $currency->id)
            ->where("used_in_" . request()->type, true)
            ->get();

        $accounts = auth()->user()->accounts()
            ->where("currency_id", $currency->id)
            ->where("used_in_" . request()->type, true)
            ->get();

        $charts = $this->getCharts("/" . request()->type);

        return response()->json(compact("categories", "accounts", "charts"));
    }

    public function list(Currency $currency)
    {
        $items = $this->getTypeRelation()
            ->where("currency_id", $currency->id)
            ->select("id", "date", "title", "amount", "price", "category_id", "account_id", DB::raw("round(amount * price, 2) AS value"));

        $fields = ["date", "title", "amount", "price", "value"];

        $data = request()->validate([
            "title" => ["nullable", "string", "min:1", "max:64"],
            "dates" => ["nullable", "array"],
            "dates.*" => ["required", "date", "distinct"],
            "categories" => ["nullable", "array"],
            "categories.*" => ["required", "integer", "exists:categories,id"],
            "accounts" => ["nullable", "array"],
            "accounts.*" => ["required", "integer", "exists:accounts,id"],
            "orderFields" => ["nullable", "array", new SameLengthAs("orderDirections")],
            "orderFields.*" => ["required", "string", "in:" . implode(",", $fields), "distinct"],
            "orderDirections" => ["nullable", "array", new SameLengthAs("orderFields")],
            "orderDirections.*" => ["nullable", "string", "in:asc,desc"]
        ]);

        if (isset($data["title"])) {
            $items->where("title", "ilike", "%" . $data["title"] . "%");
        }

        if (isset($data["dates"])) {
            $items->whereIn("date", $data["dates"]);
        }

        if (isset($data["categories"])) {
            $items->whereIn("category_id", $data["categories"]);
        }

        if (isset($data["accounts"])) {
            $items->whereIn("account_id", $data["accounts"]);
        }

        $fields = ["date", "title", "amount", "price", "value"];

        if (isset($data["orderFields"]) && isset($data["orderDirections"])) {
            foreach ($data["orderFields"] as $i => $item) {
                $fields = array_diff($fields, [$item]);
                $items = $items->orderBy($item, $data["orderDirections"][$i] ?? "asc");
            }
        }

        foreach ($fields as $field) {
            $items = $items->orderBy($field, $field == "date" ? "desc" : "asc");
        }

        $items = $this->addNamesToPaginatedIOItems($items->paginate(20), $currency);

        return response()->json(compact("items"));
    }

    public function data(Currency $currency)
    {
        $accountsAndCategories = $this->getCategoriesAndAccounts($currency);
        $titles = $this->getTitles();

        return response()->json([ ...compact("titles"), ...$accountsAndCategories ]);
    }

    public function store(Currency $currency)
    {
        $data = request()->validate([
            "data.*.date" => ["required", "date", new CorrectDateIO],
            "data.*.title" => ["required", "string", "max:64"],
            "data.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "data.*.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "data.*.category_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount($currency)],
            "data.*.account_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount($currency)],
        ]);

        if (auth()->user()->extensionCodes->contains("cashan") && request("cash")) {
            $cash = request()->validate([
                "cash" => ["required", "array"],
                "cash.*.id" => ["required", "integer", new CorrectCashCurrency($currency)],
                "cash.*.amount" => ["required", "integer", "min:0", "max:1e7", "not_in:1e7", new CashValidAmount(request()->type)]
            ])["cash"];

            foreach ($cash as $item) {
                $attachedCash = auth()->user()->cash()->find($item["id"]);

                if ($attachedCash) {
                    $cashAmount = $attachedCash->pivot->amount + $item["amount"] * (request()->type == "income" ? 1 : -1);

                    if ($cashAmount) {
                        auth()->user()->cash()->updateExistingPivot(
                            $item["id"],
                            ["amount" => $cashAmount]
                        );
                    }
                    else {
                        auth()->user()->cash()->detach($item["id"]);
                    }
                }
                else {
                    /*
                        This can only be reached if request()->type is "income",
                        in outcome cash has to be already attached and this
                        is checked inside CashValidAmount rule.
                    */

                    auth()->user()->cash()->attach(
                        $item["id"],
                        [ "amount" => $item["amount"] ]
                    );
                }
            }
        }

        foreach ($data["data"] as $item) {
            $item["currency_id"] = $currency->id;
            $this->getTypeRelation()->create($item);
        }

        return response("");
    }
}
