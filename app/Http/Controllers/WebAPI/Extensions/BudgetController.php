<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use App\Http\Controllers\Controller;
use App\Rules\EqualArrayLength;

class BudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "extension:budget"]);
    }

    public function index()
    {
        $data = request()->validate([
            "title" => ["nullable", "string", "min:1", "max:64"],
            "orderFields" => ["nullable", "array", new EqualArrayLength("orderDirections")],
            "orderFields.*" => ["required", "string", "in:id,title", "distinct"],
            "orderDirections" => ["nullable", "array", new EqualArrayLength("orderFields")],
            "orderDirections.*" => ["required", "string", "in:asc,desc"]
        ]);

        $budgets = auth()->user()->budgets()
            ->select("id", "title", "start_date", "end_date");

        if (isset($data["title"])) {
            $budgets = $budgets->where("title", "ilike", "%" . $data["title"] . "%");
        }

        if (isset($data["orderFields"]) && isset($data["orderDirections"])) {
            foreach ($data["orderFields"] as $i => $item) {
                $budgets = $budgets->orderBy($item, $data["orderDirections"][$i] ?? "asc");
            }
        } else {
            $budgets = $budgets->orderBy("id", "desc");
        }

        $budgets = $budgets->paginate(20);

        return response()->json(compact("budgets"));
    }
}
