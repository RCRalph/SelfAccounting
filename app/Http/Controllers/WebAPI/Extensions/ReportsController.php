<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Report;

use App\Rules\Common\SameLengthAs;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "extension:report"]);
    }

    private function getColumnBinary($columns)
    {
        $columnNumber = 0;
        foreach ($this->TABLE_HEAD as $key) {
            $columnNumber <<= 1;
            $columnNumber += $columns[$key];
        }

        return $columnNumber;
    }

    private function getColumnsToShow($columnNumber)
    {
        $columns = [];
        foreach (array_reverse($this->TABLE_HEAD) as $key) {
            $columns[$key] = !!($columnNumber % 2);
            $columnNumber >>= 1;
        }

        return $columns;
    }

    public function index()
    {
        $owners = auth()->user()->sharedReports()
            ->select("users.id", "users.username")
            ->join("users", "reports.user_id", "=", "users.id")
            ->distinct()
            ->get()
            ->makeHidden("pivot");

        return response()->json(compact("owners"));
    }

    public function ownedReports()
    {
        $reports = auth()->user()->reports()
            ->select("users.username AS owner")
            ->select("id", "title");

        $data = request()->validate([
            "items" => ["required", "integer", "in:10,15,20,25,30"],
            "search" => ["nullable", "string", "min:1", "max:64"],
            "orderFields" => ["nullable", "array", new SameLengthAs("orderDirections")],
            "orderFields.*" => ["required", "string", "in:id,title", "distinct"],
            "orderDirections" => ["nullable", "array", new SameLengthAs("orderFields")],
            "orderDirections.*" => ["required", "string", "in:asc,desc"]
        ]);

        if (isset($data["search"])) {
            $reports->where("title", "ilike", "%" . $data["search"] . "%");
        }

        if (isset($data["orderFields"]) && isset($data["orderDirections"])) {
            foreach ($data["orderFields"] as $i => $item) {
                $reports = $reports->orderBy($item, $data["orderDirections"][$i] ?? "asc");
            }
        }

        $reports = $reports->paginate($data["items"]);

        return response()->json(compact("reports"));
    }

    public function sharedReports()
    {
        $reports = auth()->user()->sharedReports()
            ->select("reports.id", "reports.title", "users.username AS owner")
            ->join("users", "reports.user_id", "=", "users.id");

        $data = request()->validate([
            "items" => ["required", "integer", "in:10,15,20,25,30"],
            "search" => ["nullable", "string", "min:1", "max:64"],
            "owners" => ["nullable", "array"],
            "owners.*" => ["required", "integer", "exists:users,id"],
            "orderFields" => ["nullable", "array", new SameLengthAs("orderDirections")],
            "orderFields.*" => ["required", "string", "in:id,title", "distinct"],
            "orderDirections" => ["nullable", "array", new SameLengthAs("orderFields")],
            "orderDirections.*" => ["required", "string", "in:asc,desc"]
        ]);

        if (isset($data["search"])) {
            $reports->where("title", "ilike", "%" . $data["search"] . "%");
        }

        if (isset($data["owners"])) {
            $reports->whereIn("users.user_id", $data["owners"]);
        }

        if (isset($data["orderFields"]) && isset($data["orderDirections"])) {
            foreach ($data["orderFields"] as $i => $item) {
                $reports = $reports->orderBy($item, $data["orderDirections"][$i] ?? "asc");
            }
        }

        $reports = $reports->paginate($data["items"]);

        return response()->json(compact("reports"));
    }

    public function show(Report $report)
    {
        $this->authorize("view", $report);

        $reportData = [
            "title" => $report->title,
            "columns" => $this->getColumnsToShow($report->show_columns)
        ];

        $categories = $report->user->categories()
            ->select("id", "name", "currency_id")
            ->get()
            ->groupBy("currency_id");

        $means = $report->user->meansOfPayment()
            ->select("id", "name", "currency_id")
            ->get()
            ->groupBy("currency_id");

        $rows = $report->additionalEntries()->select(
            "date", "title", "amount", "price",
            DB::raw("round(amount * price, 2) AS value"),
            "currency_id", "category_id", "mean_id"
        );

        foreach ($report->queries as $query) {
            $data = $query->query_data == "income" ?
                $report->user->income() :
                $report->user->outcome();

            $valueSign = ($report->income_addition xor $query->query_data == "income") ? -1 : 1;

            $queryFieldsToCheck = [
                [ "column" => "date", "operator" => ">=", "name" => "min_date" ],
                [ "column" => "date", "operator" => "<=", "name" => "max_date" ],
                [ "column" => "title", "operator" => "=", "name" => "title" ],
                [ "column" => "amount", "operator" => ">=", "name" => "min_amount" ],
                [ "column" => "amount", "operator" => "<=", "name" => "max_amount" ],
                [ "column" => "price", "operator" => ">=", "name" => "min_price" ],
                [ "column" => "price", "operator" => "<=", "name" => "max_price" ],
                [ "column" => "currency_id", "operator" => "=", "name" => "currency_id" ],
                [ "column" => "category_id", "operator" => "=", "name" => "category_id" ],
                [ "column" => "mean_id", "operator" => "=", "name" => "mean_id" ]
            ];

            foreach ($queryFieldsToCheck as $field) {
                if ($query[$field["name"]]) {
                    $data->where($field["column"], $field["operator"], $query[$field["name"]]);
                }
            }

            $rows->union($data->select(
                "date", "title", DB::raw("amount * $valueSign AS amount"),
                "price", DB::raw("round(amount * price * $valueSign, 2) AS value"),
                "currency_id", "category_id", "mean_id"
            ));
        }

        dd($rows->get());
    }
}
