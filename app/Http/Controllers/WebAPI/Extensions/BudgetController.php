<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use App\Http\Controllers\Controller;
use App\Models\Budget;
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

    public function store()
    {
        $data = request()->validate([
            "title" => ["required", "string", "max:64"],
            "start_date" => ["required", "date", "date_format:Y-m-d", "before_or_equal:end_date"],
            "end_date" => ["required", "date", "date_format:Y-m-d", "after_or_equal:start_date"],
        ]);

        $budget = auth()->user()->budgets()->create($data);

        return response()->json(["id" => $budget->id]);
    }

    public function duplicate(Budget $budget)
    {
        $this->authorize("duplicate", $budget);

        $duplicate = Budget::create($budget->makeHidden(["id", "created_at", "updated_at"])->toArray());

        foreach ($budget->entries as $entry) {
            $duplicate->entries()->create($entry->makeHidden(["id", "budget_id", "created_at", "updated_at"])->toArray());
        }

        return response()->json(["id" => $duplicate->id]);
    }

    public function edit(Budget $budget)
    {
        $this->authorize("update", $budget);

        $data = $budget->only("title", "start_date", "end_date");

        return response()->json(compact("data"));
    }

    public function update(Budget $budget)
    {
        $this->authorize("update", $budget);

        $data = request()->validate([
            "title" => ["required", "string", "max:64"],
            "start_date" => ["required", "date", "date_format:Y-m-d", "before_or_equal:end_date"],
            "end_date" => ["required", "date", "date_format:Y-m-d", "after_or_equal:start_date"],
        ]);

        $budget->update($data);

        return response("");
    }
}