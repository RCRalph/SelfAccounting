<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Report;

use App\Rules\Common\SameLengthAs;

class ReportsController extends Controller
{
    private $columns = ["date", "title", "amount", "price", "value", "category_id", "mean_id"];

    private $queryFields = [
        [ "column" => "date", "operator" => ">=", "name" => "min_date" ],
        [ "column" => "amount", "operator" => ">=", "name" => "min_amount" ],
        [ "column" => "price", "operator" => ">=", "name" => "min_price" ],
        [ "column" => "date", "operator" => "<=", "name" => "max_date" ],
        [ "column" => "amount", "operator" => "<=", "name" => "max_amount" ],
        [ "column" => "price", "operator" => "<=", "name" => "max_price" ],
        [ "column" => "title", "operator" => "=" ],
        [ "column" => "currency_id", "operator" => "=" ],
        [ "column" => "category_id", "operator" => "=" ],
        [ "column" => "mean_id", "operator" => "=" ]
    ];

    public function __construct()
    {
        $this->middleware(["auth", "extension:report"]);
    }

    private function getColumnBinary($columns)
    {
        $columnNumber = 0;
        foreach ($this->columns as $key) {
            $columnNumber <<= 1;
            $columnNumber += $columns[$key];
        }

        return $columnNumber;
    }

    private function getColumnsToShow($columnNumber)
    {
        $columns = [];
        foreach (array_reverse($this->columns) as $key) {
            $columns[$key] = !!($columnNumber % 2);
            $columnNumber >>= 1;
        }

        return $columns;
    }

    private function addFields(&$data, $columnsToShow, $valueFieldMultiplier = 1)
    {
        $data->select("currency_id");

        foreach ($this->getColumnsToShow($columnsToShow) as $column => $show) {
            if ($show) {
                if ($column == "value") {
                    $data->addSelect(DB::raw("round(amount * price * $valueFieldMultiplier, 2) AS value"));
                }
                else {
                    $data->addSelect($column);
                }
            }
        }
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

        $information = [
            "title" => $report->title,
            "owner" => $report->user->only("username", "profile_picture_link")
        ];

        $valueSign = 1;
        $rows = $report->additionalEntries();
        $this->addFields($rows, $report->show_columns);

        foreach ($report->queries as $query) {
            $data = $query->query_data == "income" ?
                $report->user->income() :
                $report->user->outcome();

            foreach ($this->queryFields as $field) {
                $queryFieldName = $field["name"] ?? $field["column"];

                if ($query[$queryFieldName]) {
                    $data->where($field["column"], $field["operator"], $query[$queryFieldName]);
                }
            }

            $this->addFields($data, $report->show_columns,
                ($report->income_addition xor $query->query_data == "income") ? -1 : 1
            );

            $rows->union($data);
        }

        $items = $rows->orderBy("date", $report->sort_dates_desc ? "desc" : "asc")->get();

        if ($report->calculate_sum) {
            $information["sum"] = [];

            foreach ($items as $row) {
                if (isset($information["sum"][$row["currency_id"]])) {
                    $information["sum"][$row["currency_id"]] += $row["value"];
                }
                else {
                    $information["sum"][$row["currency_id"]] = $row["value"];
                }
            }

            foreach ($information["sum"] as $i => $s) {
                $information["sum"][$i] = round($information["sum"][$i], 2);
            }
        }

        $categories = $report->user->categories()
            ->select("id", "name")
            ->get()
            ->mapWithKeys(fn ($item) => [$item["id"] => $item["name"]])
            ->toArray();

        $means = $report->user->meansOfPayment()
            ->select("id", "name")
            ->get()
            ->mapWithKeys(fn ($item) => [$item["id"] => $item["name"]])
            ->toArray();

        $showColumns = $this->getColumnsToShow($report->show_columns);

        foreach ($items as $i => $item) {
            if ($showColumns["amount"]) {
                $items[$i]["amount"] *= 1;
            }

            if ($showColumns["price"]) {
                $items[$i]["price"] *= 1;
            }

            if ($showColumns["value"]) {
                $items[$i]["value"] *= 1;
            }

            if ($showColumns["category_id"]) {
                $items[$i]["category"] = $categories[$item["category_id"]] ?? "N/A";
                unset($items[$i]["category_id"]);
            }

            if ($showColumns["mean_id"]) {
                $items[$i]["mean"] = $means[$item["mean_id"]] ?? "N/A";
                unset($items[$i]["mean_id"]);
            }
        }

        return response()->json(compact("information", "items"));
    }

    public function create()
    {
        $titles = $this->getTitles();

        $categories = auth()->user()->categories()
            ->select("id", "name", "currency_id")
            ->get()
            ->groupBy("currency_id");

        $means = auth()->user()->meansOfPayment()
            ->select("id", "name", "currency_id")
            ->get()
            ->groupBy("currency_id");

        return response()->json(compact("titles", "categories", "means"));
    }
}
