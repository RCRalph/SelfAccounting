<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use App\Models\Currency;

use App\Rules\Transactions\CorrectTransactionDate;
use App\Rules\Transactions\ValidCategoryOrAccount;
use App\Rules\EqualArrayLength;
use App\Rules\Extensions\Cash\CorrectCashCurrency;
use App\Rules\Extensions\Cash\ValidCashAmount;

class TransactionsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function getCategoriesAndAccounts($currency)
    {
        $categories = auth()->user()->categories()
            ->select("id", "name", "icon")
            ->where("currency_id", $currency->id)
            ->where("used_in_" . request()->type, true)
            ->orderBy("name")
            ->get()
            ->prepend(["id" => null, "name" => "N/A"]);

        $accounts = auth()->user()->accounts()
            ->select("id", "name", "icon", "start_date")
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
            "date" => ["required", "date", new CorrectTransactionDate],
            "title" => ["required", "string", "max:64"],
            "amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "currency_id" => ["required", "integer", "exists:currencies,id"],
            "category_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount],
            "account_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount]
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
            ->select("id", "name", "icon")
            ->where("currency_id", $currency->id)
            ->where("used_in_" . request()->type, true)
            ->orderBy("name")
            ->get();

        $accounts = auth()->user()->accounts()
            ->select("id", "name", "icon")
            ->where("currency_id", $currency->id)
            ->where("used_in_" . request()->type, true)
            ->orderBy("name")
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
            "orderFields" => ["nullable", "array", new EqualArrayLength("orderDirections")],
            "orderFields.*" => ["required", "string", "in:" . implode(",", $fields), "distinct"],
            "orderDirections" => ["nullable", "array", new EqualArrayLength("orderFields")],
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

        if (isset($data["orderFields"]) && isset($data["orderDirections"])) {
            foreach ($data["orderFields"] as $i => $item) {
                $fields = array_diff($fields, [$item]);
                $items = $items->orderBy($item, $data["orderDirections"][$i] ?? "asc");
            }
        }

        foreach ($fields as $field) {
            $items = $items->orderBy($field, $field == "date" ? "desc" : "asc");
        }

        $items = $this->addNamesToPaginatedTransactionsItems($items->paginate(20), $currency);

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
            "data.*.date" => ["required", "date", new CorrectTransactionDate],
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
                "cash.*.amount" => ["required", "integer", "min:0", new ValidCashAmount(request()->type == "income")]
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
                        in expenses cash has to be already attached and this
                        is checked inside ValidCashAmount rule.
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

    public function convert($id)
    {
        $data = $this->getTypeRelation()
            ->select("id", "date", "title", "amount", "price", "category_id", "account_id", "currency_id")
            ->where("id", $id)
            ->get()
            ->firstOrFail()
            ->toArray();

        if (request()->type == "income") {
            auth()->user()->expenses()->create($data);
            auth()->user()->income()->where("id", $id)->delete();
        } else {
            auth()->user()->income()->create($data);
            auth()->user()->expenses()->where("id", $id)->delete();
        }

        return response("");
    }
}
