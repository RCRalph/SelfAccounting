<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\Currency;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index(Currency $currency)
    {
        $data = auth()->user()->categories()
            ->select("id", "icon", "name", "used_in_income", "used_in_expenses", "show_on_charts", "count_to_summary", "start_date", "end_date")
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
            "start_date" => ["present", "date", "nullable", "before_or_equal:end_date"],
            "end_date" => ["present", "date", "nullable", "after_or_equal:start_date"]
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
            "icon" => ["present", "nullable", "string", "max:64"],
            "name" => ["required", "string", "max:32"],
            "used_in_income" => ["required", "boolean"],
            "used_in_expenses" => ["required", "boolean"],
            "show_on_charts" => ["required", "boolean"],
            "count_to_summary" => ["required", "boolean"],
            "start_date" => ["present", "date", "nullable", "before_or_equal:end_date"],
            "end_date" => ["present", "date", "nullable", "after_or_equal:start_date"]
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

    public function icons()
    {
        $icons = Category::select("icon", DB::raw("COUNT(icon) AS count"))
            ->whereNotNull("icon")
            ->groupBy("icon")
            ->orderBy("count", "DESC")
            ->orderBy("icon", "DESC")
            ->limit(16)
            ->pluck("icon");

        foreach (Category::$ICONS as $icon) {
            if ($icons->count() == 16) {
                break;
            } else if (!$icons->contains($icon)) {
                $icons->push($icon);
            }
        }

        return response()->json(compact("icons"));
    }

    public function duplicate(Category $category)
    {
        $this->authorize("duplicate", $category);

        $currency = request()->validate([
            "currency" => ["required", "exists:currencies,id"]
        ])["currency"];

        $name = $category->name;
        if ($currency == $category->currency_id) {
            $name .= " - duplicate";
        }

        auth()->user()->categories()->create([
            ...$category->toArray(),
            "name" => $name,
            "currency_id" => $currency
        ]);

        return response("");
    }
}
