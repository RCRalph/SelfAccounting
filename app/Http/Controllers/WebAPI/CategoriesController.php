<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Currency;
use App\Models\Category;

use App\Rules\Common\DateBeforeOrEqualField;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index(Currency $currency)
    {
        $data = auth()->user()->categories()
            ->select("id", "name", "used_in_income", "used_in_outcome", "show_on_charts", "count_to_summary", "start_date", "end_date")
            ->where("currency_id", $currency->id)
            ->orderBy("name")
            ->get();

        return response()->json($data);
    }

    public function create(Currency $currency)
    {
        $data = request()->validate([
            "name" => ["required", "string", "max:32"],
            "used_in_income" => ["required", "boolean"],
            "used_in_outcome" => ["required", "boolean"],
            "show_on_charts" => ["required", "boolean"],
            "count_to_summary" => ["required", "boolean"],
            "start_date" => ["present", "date", "nullable", new DateBeforeOrEqualField("end_date")],
            "end_date" => ["present", "date", "nullable"]
        ]);

        auth()->user()->categories()->create([ ...$data, "currency_id" => $currency->id ]);

        return response("");
    }

    public function show(Category $category)
    {
        $this->authorize("view", $category);

        return response()->json([ "data" => $category->makeHidden(["user_id", "currency_id", "created_at", "updated_at"]) ]);
    }

    public function update(Category $category)
    {
        $this->authorize("update", $category);

        $data = request()->validate([
            "name" => ["required", "string", "max:32"],
            "used_in_income" => ["required", "boolean"],
            "used_in_outcome" => ["required", "boolean"],
            "show_on_charts" => ["required", "boolean"],
            "count_to_summary" => ["required", "boolean"],
            "start_date" => ["present", "date", "nullable", new DateBeforeOrEqualField("end_date")],
            "end_date" => ["present", "date", "nullable"]
        ]);

        $category->update($data);

        return response("");
    }

    public function delete(Category $category)
    {
        $this->authorize("delete", $category);

        $category->delete();

        return response("");
    }
}
