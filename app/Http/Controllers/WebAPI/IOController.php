<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use App\Income;
use App\Outcome;
use App\Currency;
use App\Chart;

use App\Rules\CorrectDateIO;
use App\Rules\CorrectDateIOUpdate;
use App\Rules\ValidCategoryOrMeanUpdate;
use App\Rules\ValidCategoryMean;
use App\Rules\SameLengthAs;

class IOController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function getTypeRelation()
    {
        return request()->type == "income" ?
            auth()->user()->income() :
            auth()->user()->outcome();
    }

    private function getCategoriesAndMeans($currency)
    {
        $categories = auth()->user()->categories()
            ->select("id", "name")
            ->where("currency_id", $currency->id)
            ->where(request()->type . "_category", true)
            ->orderBy("name")
            ->get()
            ->prepend(["id" => null, "name" => "N/A"]);

        $means = auth()->user()->meansOfPayment()
            ->select("id", "name", "first_entry_date")
            ->where("currency_id", $currency->id)
            ->where(request()->type . "_mean", true)
            ->orderBy("name")
            ->get()
            ->prepend(["id" => null, "name" => "N/A", "first_entry_date" => "1970-01-01"]);

        return compact("categories", "means");
    }

    public function show($id)
    {
        $data = $this->getTypeRelation()
            ->select("id", "date", "title", "amount", "price", "category_id", "mean_id", "currency_id")
            ->where("id", $id)
            ->get()->firstOrFail();

        $data->price *= 1;
        $data->amount *= 1;

        $meansAndCategories = $this->getCategoriesAndMeans($data->currency);

        $data = collect($data);
        $data->forget("currency");

        return response()->json([ ...compact("data"), ...$meansAndCategories ]);
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
            "category_id" => ["present", "nullable", "integer", new ValidCategoryOrMeanUpdate(request()->type)],
            "mean_id" => ["present", "nullable", "integer", new ValidCategoryOrMeanUpdate(request()->type)]
        ]);

        $toUpdate->update($data);

        return response("", 200);
    }

    public function destroy($id)
    {
        $toDestroy = $this->getTypeRelation()
            ->where("id", $id)
            ->get()->firstOrFail()->delete();

        return response("", 200);
    }

    public function overview(Currency $currency) {
        $categories = auth()->user()->categories()
            ->where("currency_id", $currency->id)
            ->where(request()->type . "_category", true)
            ->get();

        $means = auth()->user()->meansOfPayment()
            ->where("currency_id", $currency->id)
            ->where(request()->type . "_mean", true)
            ->get();

        $charts = Chart::where("name", "ilike", "%" . request()->type . "%")->get();

        return response()->json(compact("categories", "means", "charts"));
    }

    public function list(Currency $currency)
    {
        $items = $this->getTypeRelation()
            ->where("currency_id", $currency->id)
            ->select("id", "date", "title", "amount", "price", "category_id", "mean_id", DB::raw("round(amount * price, 2) AS value"));

        $fields = ["date", "title", "amount", "price", "value"];

        $data = request()->validate([
            "title" => ["nullable", "string", "min:1", "max:64"],
            "dates" => ["nullable", "array"],
            "dates.*" => ["required", "date", "distinct"],
            "categories" => ["nullable", "array"],
            "categories.*" => ["required", "integer", "exists:categories,id"],
            "means" => ["nullable", "array"],
            "means.*" => ["required", "integer", "exists:mean_of_payments,id"],
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

        if (isset($data["means"])) {
            $items->whereIn("mean_id", $data["means"]);
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
        $meansAndCategories = $this->getCategoriesAndMeans($data->currency);

        return response()->json([...$meansAndCategories]);
    }

    public function store()
    {
        $data = request()->validate([
            "data.*.date" => ["required", "date", new CorrectDateIO],
            "data.*.title" => ["required", "string", "max:64"],
            "data.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "data.*.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "data.*.currency_id" => ["required", "integer", "exists:currencies,id"],
            "data.*.category_id" => ["present", "nullable", "integer", new ValidCategoryMean(request()->type)],
            "data.*.mean_id" => ["present", "nullable", "integer", new ValidCategoryMean(request()->type)],
        ]);

        foreach ($data["data"] as $item) {
            $this->getTypeRelation()->create($item);
        }

        return response("");
    }
}
