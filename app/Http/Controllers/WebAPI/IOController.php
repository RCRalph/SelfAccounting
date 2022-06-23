<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

use App\Income;
use App\Outcome;

use App\Rules\CorrectDateIOUpdate;
use App\Rules\ValidCategoryOrMeanUpdate;

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

    public function show($id)
    {
        $data = $this->getTypeRelation()
            ->select("id", "date", "title", "amount", "price", "category_id", "mean_id", "currency_id")
            ->where("id", $id)
            ->get()->firstOrFail();

        $data["price"] *= 1;
        $data["amount"] *= 1;

        $means = auth()->user()->meansOfPayment()
            ->select("id", "name", "first_entry_date")
            ->where("currency_id", $data->currency_id)
            ->where(request()->type . "_mean", true)
            ->get()
            ->prepend(["id" => null, "name" => "N/A", "first_entry_date" => "1970-01-01"]);

        $categories = auth()->user()->categories()
            ->select("id", "name")
            ->where("currency_id", $data->currency_id)
            ->where(request()->type . "_category", true)
            ->get()
            ->prepend(["id" => null, "name" => "N/A"]);

        return response()->json(compact("data", "means", "categories"));
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
}
