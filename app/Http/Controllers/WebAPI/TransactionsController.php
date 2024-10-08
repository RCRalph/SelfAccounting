<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use App\Models\Currency;
use App\Models\Chart;

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

        $data = collect($data);
        $data->forget("currency");

        return response()->json([...compact("data"), ...$accountsAndCategories]);
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

        $charts = Chart::route("/" . request()->type);

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

        $accountIDsQuery = $this->getTypeRelation()
            ->select("account_id")
            ->whereBetween("date", [now()->subDays(30)->startOfDay(), now()->endOfDay()]);

        $mostUsedAccountID = DB::query()
            ->select("account_id", DB::raw("COUNT(*) AS count"))
            ->fromSub($accountIDsQuery, "accounts")
            ->orderBy("count", "desc")
            ->groupBy("account_id")
            ->first()
            ->account_id ?? null;

        $categoryIDsQuery = $this->getTypeRelation()
            ->select("category_id")
            ->whereBetween("date", [now()->subDays(30)->startOfDay(), now()->endOfDay()]);

        $mostUsedCategoryID = DB::query()
            ->select("category_id", DB::raw("COUNT(*) AS count"))
            ->fromSub($categoryIDsQuery, "categories")
            ->orderBy("count", "desc")
            ->groupBy("category_id")
            ->first()
            ->category_id ?? null;

        $titles = auth()->user()->transactionTitles;

        return response()->json([
            ...compact("titles", "mostUsedCategoryID", "mostUsedAccountID"),
            ...$accountsAndCategories
        ]);
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
                "cash.*.amount" => ["required", "integer", new ValidCashAmount(request()->type)]
            ])["cash"];

            foreach ($cash as $item) {
                $ownedCash = auth()->user()->cash()->find($item["id"])->pivot->amount ?? 0;

                $cashAmount = match (request()->type) {
                    "income" => $ownedCash + $item["amount"],
                    "expenses" => $ownedCash - $item["amount"],
                    default => 0,
                };

                if ($ownedCash) {
                    if ($cashAmount) {
                        auth()->user()->cash()->updateExistingPivot(
                            $item["id"],
                            ["amount" => $cashAmount]
                        );
                    } else {
                        auth()->user()->cash()->detach($item["id"]);
                    }
                } else if ($cashAmount) {
                    auth()->user()->cash()->attach(
                        $item["id"],
                        ["amount" => $cashAmount]
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

    public function titles(Currency $currency)
    {
        $title = request()->validate([
            "title" => ["required", "string", "max:64"],
        ])["title"];

        return response()->json([
            "titles" => $this->getTitles($title, $currency->id)
        ]);
    }
}
