<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\Report;
use App\Models\User;

use App\Rules\EqualArrayLength;
use App\Rules\Extensions\Reports\ValidCategoryOrAccount;
use App\Rules\Extensions\Reports\BelongsToReport;

class ReportsController extends Controller
{
    private $columns = ["date", "title", "amount", "price", "value", "category_id", "account_id"];

    private $queryFields = [
        ["column" => "date", "operator" => ">=", "name" => "min_date"],
        ["column" => "amount", "operator" => ">=", "name" => "min_amount"],
        ["column" => "price", "operator" => ">=", "name" => "min_price"],
        ["column" => "date", "operator" => "<=", "name" => "max_date"],
        ["column" => "amount", "operator" => "<=", "name" => "max_amount"],
        ["column" => "price", "operator" => "<=", "name" => "max_price"],
        ["column" => "title", "operator" => "="],
        ["column" => "currency_id", "operator" => "="],
        ["column" => "category_id", "operator" => "="],
        ["column" => "account_id", "operator" => "="]
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
                } else {
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
        $data = request()->validate([
            "items" => ["required", "integer", "in:10,15,20,25,30"],
            "search" => ["nullable", "string", "min:1", "max:64"],
            "orderFields" => ["nullable", "array", new EqualArrayLength("orderDirections")],
            "orderFields.*" => ["required", "string", "in:id,title", "distinct"],
            "orderDirections" => ["nullable", "array", new EqualArrayLength("orderFields")],
            "orderDirections.*" => ["required", "string", "in:asc,desc"]
        ]);

        $reports = auth()->user()->reports()
            ->select("id", "title");

        if (isset($data["search"])) {
            $reports->where("title", "ilike", "%" . $data["search"] . "%");
        }

        if (isset($data["orderFields"]) && isset($data["orderDirections"])) {
            foreach ($data["orderFields"] as $i => $item) {
                $reports = $reports->orderBy($item, $data["orderDirections"][$i] ?? "asc");
            }
        } else {
            $reports = $reports->orderBy("id", "desc");
        }

        $reports = $reports->paginate($data["items"]);

        return response()->json(compact("reports"));
    }

    public function sharedReports()
    {
        $data = request()->validate([
            "items" => ["required", "integer", "in:10,15,20,25,30"],
            "search" => ["nullable", "string", "min:1", "max:64"],
            "owners" => ["nullable", "array"],
            "owners.*" => ["required", "integer", "exists:users,id"],
            "orderFields" => ["nullable", "array", new EqualArrayLength("orderDirections")],
            "orderFields.*" => ["required", "string", "in:id,title", "distinct"],
            "orderDirections" => ["nullable", "array", new EqualArrayLength("orderFields")],
            "orderDirections.*" => ["required", "string", "in:asc,desc"]
        ]);

        $reports = auth()->user()->sharedReports()
            ->join("users", "reports.user_id", "=", "users.id");

        if (isset($data["search"])) {
            $reports->where("title", "ilike", "%" . $data["search"] . "%");
        }

        if (isset($data["owners"])) {
            $reports->whereIn("users.id", $data["owners"]);
        }

        if (isset($data["orderFields"]) && isset($data["orderDirections"])) {
            foreach ($data["orderFields"] as $i => $item) {
                $reports->orderBy($item, $data["orderDirections"][$i] ?? "asc");
            }
        } else {
            $reports->orderBy("id", "desc");
        }

        $reports = $reports
            ->paginate($data["items"], ["reports.title", "reports.id", "users.username AS owner"]);

        return response()->json(compact("reports"));
    }

    public function show(Report $report)
    {
        $this->authorize("view", $report);

        $reports = auth()->user()->reports()
            ->orderBy("id", "asc")
            ->pluck("id");

        $canEdit = $report->user_id == auth()->user()->id;

        $information = [
            "title" => $report->title,
            "owner" => $report->user->only("username", "profile_picture_link")
        ];

        $rows = $report->additionalEntries();
        $this->addFields($rows, $report->show_columns);

        foreach ($report->queries as $query) {
            $data = $query->query_data == "income" ?
                $report->user->income() :
                $report->user->expenses();

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
                } else {
                    $information["sum"][$row["currency_id"]] = $row["value"];
                }
            }

            foreach ($information["sum"] as $i => $s) {
                $information["sum"][$i] = round($information["sum"][$i], 2);
            }
        }

        $categories = $report->user->categories()
            ->select("id", "name", "icon")
            ->get()
            ->mapWithKeys(fn($item) => [$item["id"] => [
                "name" => $item["name"],
                "icon" => $item["icon"]
            ]])
            ->toArray();

        $accounts = $report->user->accounts()
            ->select("id", "name", "icon")
            ->get()
            ->mapWithKeys(fn($item) => [$item["id"] => [
                "name" => $item["name"],
                "icon" => $item["icon"]
            ]])
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
                $items[$i]["category"] = [
                    "name" => $categories[$item["category_id"]]["name"] ?? "N/A",
                    "icon" => $categories[$item["category_id"]]["icon"] ?? null
                ];

                unset($items[$i]["category_id"]);
            }

            if ($showColumns["account_id"]) {
                $items[$i]["account"] = [
                    "name" => $accounts[$item["account_id"]]["name"] ?? "N/A",
                    "icon" => $accounts[$item["account_id"]]["icon"] ?? null
                ];

                unset($items[$i]["account_id"]);
            }
        }

        return response()->json(compact("information", "items", "reports", "canEdit"));
    }

    public function create()
    {
        $titles = auth()->user()->transactionTitles;

        $categories = auth()->user()->categories()
            ->select("id", "name", "icon", "currency_id")
            ->get()
            ->groupBy("currency_id");

        $accounts = auth()->user()->accounts()
            ->select("id", "name", "icon", "currency_id")
            ->get()
            ->groupBy("currency_id");

        return response()->json(compact("titles", "categories", "accounts"));
    }

    public function userInfo()
    {
        $email = request()->validate([
            "email" => ["required", "email", "max:64", "exists:users,email", "not_in:" . auth()->user()->email]
        ])["email"];

        $user = User::select("email", "username", "profile_picture")
            ->where("email", $email)->first()
            ->only("email", "username", "profile_picture_link");

        return response()->json(compact("user"));
    }

    public function titles()
    {
        $title = request()->validate([
            "title" => ["required", "string", "max:64"],
        ])["title"];

        return response()->json([
            "titles" => $this->getTitles($title)
        ]);
    }

    public function store()
    {
        $data = request()->validate([
            "title" => ["required", "string", "max:64"],
            "income_addition" => ["required", "boolean"],
            "sort_dates_desc" => ["required", "boolean"],
            "calculate_sum" => ["required", "boolean"]
        ]);

        $columns = request()->validate([
            "columns" => ["required", "array"],
            "columns.date" => ["required", "boolean"],
            "columns.title" => ["required", "boolean"],
            "columns.amount" => ["required", "boolean"],
            "columns.price" => ["required", "boolean"],
            "columns.value" => ["required", "boolean"],
            "columns.category_id" => ["required", "boolean"],
            "columns.account_id" => ["required", "boolean"]
        ])["columns"];

        $queries = request()->validate([
            "queries" => ["present", "array"],
            "queries.*.query_data" => ["required", "string", "in:income,expenses"],
            "queries.*.min_date" => ["present", "nullable", "date", "before_or_equal:queries.*.max_date"],
            "queries.*.max_date" => ["present", "nullable", "date", "after_or_equal:queries.*.min_date"],
            "queries.*.title" => ["present", "nullable", "string", "max:64"],
            "queries.*.min_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:1e7", "lte:queries.*.max_amount"],
            "queries.*.max_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:1e7", "gte:queries.*.min_amount"],
            "queries.*.min_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:1e11", "lte:queries.*.max_price"],
            "queries.*.max_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:1e11", "gte:queries.*.min_price"],
            "queries.*.currency_id" => ["present", "nullable", "integer", "exists:currencies,id"],
            "queries.*.category_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount],
            "queries.*.account_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount],
        ])["queries"];

        $additionalTransactions = request()->validate([
            "additionalTransactions" => ["present", "array"],
            "additionalTransactions.*.date" => ["required", "date", "after_or_equal:1970-01-01"],
            "additionalTransactions.*.title" => ["required", "string", "max:64"],
            "additionalTransactions.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:1e7"],
            "additionalTransactions.*.price" => ["required", "numeric", "max:1e11", "min:-1e11", "not_in:1e11,-1e11"],
            "additionalTransactions.*.currency_id" => ["required", "integer", "exists:currencies,id"],
            "additionalTransactions.*.category_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount],
            "additionalTransactions.*.account_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount],
        ])["additionalTransactions"];

        $users = request()->validate([
            "users" => ["present", "array"],
            "users.*" => ["required", "email", "max:64", "distinct", "exists:users,email", "not_in:" . auth()->user()->email]
        ])["users"];

        $data["show_columns"] = $this->getColumnBinary($columns);
        $report = auth()->user()->reports()->create($data);

        foreach ($queries as $query) {
            $report->queries()->create($query);
        }

        foreach ($additionalTransactions as $transaction) {
            $report->additionalEntries()->create($transaction);
        }

        $report->sharedUsers()->attach(User::whereIn("email", $users)->pluck("id"));

        return response()->json(["id" => $report->id]);
    }

    public function destroy(Report $report)
    {
        $this->authorize("view", $report);

        if ($report->user_id == auth()->user()->id) {
            $report->delete();
        } else {
            $report->sharedUsers()->detach(auth()->user()->id);
        }

        return response("", 200);
    }

    public function duplicate(Report $report)
    {
        $this->authorize("duplicate", $report);

        $duplicate = Report::create($report->makeHidden(["id", "created_at", "updated_at"])->toArray());

        foreach ($report->queries as $query) {
            $duplicate->queries()->create($query->makeHidden(["id", "report_id", "created_at", "updated_at"])->toArray());
        }

        foreach ($report->additionalEntries as $entry) {
            $duplicate->additionalEntries()->create($entry->makeHidden(["id", "report_id", "created_at", "updated_at"])->toArray());
        }

        $duplicate->sharedUsers()->attach($report->sharedUsers()->pluck("id"));

        return response()->json(["id" => $duplicate->id]);
    }

    public function edit(Report $report)
    {
        $this->authorize("update", $report);

        $data = $report->only("title", "income_addition", "sort_dates_desc", "calculate_sum");

        $data["columns"] = $this->getColumnsToShow($report->show_columns);

        $data["users"] = $report->sharedUsers
            ->map(fn($item) => $item->only("username", "email", "profile_picture_link"));

        $data["queries"] = $report->queries
            ->makeHidden(["report_id", "created_at", "updated_at"])
            ->map(function ($item) {
                foreach (["min_amount", "max_amount", "min_price", "max_price"] as $key) {
                    if ($item[$key] !== null) {
                        $item[$key] *= 1;
                    }
                }

                return $item;
            });

        $data["additionalTransactions"] = $report->additionalEntries
            ->makeHidden(["report_id", "created_at", "updated_at"])
            ->map(function ($item) {
                foreach (["amount", "price"] as $key) {
                    if ($item[$key] !== null) {
                        $item[$key] *= 1;
                    }
                }

                return $item;
            });

        $titles = auth()->user()->transactionTitles;

        $categories = auth()->user()->categories()
            ->select("id", "name", "icon", "currency_id")
            ->get()
            ->groupBy("currency_id");

        $accounts = auth()->user()->accounts()
            ->select("id", "name", "icon", "currency_id")
            ->get()
            ->groupBy("currency_id");

        return response()->json(compact("data", "titles", "categories", "accounts"));
    }

    public function update(Report $report)
    {
        $data = request()->validate([
            "title" => ["required", "string", "max:64"],
            "income_addition" => ["required", "boolean"],
            "sort_dates_desc" => ["required", "boolean"],
            "calculate_sum" => ["required", "boolean"]
        ]);

        $columns = request()->validate([
            "columns" => ["required", "array"],
            "columns.date" => ["required", "boolean"],
            "columns.title" => ["required", "boolean"],
            "columns.amount" => ["required", "boolean"],
            "columns.price" => ["required", "boolean"],
            "columns.value" => ["required", "boolean"],
            "columns.category_id" => ["required", "boolean"],
            "columns.account_id" => ["required", "boolean"]
        ])["columns"];

        $queries = request()->validate([
            "queries" => ["present", "array"],
            "queries.*.id" => ["nullable", "integer", "distinct", "exists:report_queries,id", new BelongsToReport($report)],
            "queries.*.query_data" => ["required", "string", "in:income,expenses"],
            "queries.*.min_date" => ["present", "nullable", "date", "before_or_equal:queries.*.max_date"],
            "queries.*.max_date" => ["present", "nullable", "date", "after_or_equal:queries.*.min_date"],
            "queries.*.title" => ["present", "nullable", "string", "max:64"],
            "queries.*.min_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:1e7", "lte:queries.*.max_amount"],
            "queries.*.max_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:1e7", "gte:queries.*.min_amount"],
            "queries.*.min_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:1e11", "lte:queries.*.max_price"],
            "queries.*.max_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:1e11", "gte:queries.*.min_price"],
            "queries.*.currency_id" => ["present", "nullable", "integer", "exists:currencies,id"],
            "queries.*.category_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount],
            "queries.*.account_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount],
        ])["queries"];

        $additionalTransactions = request()->validate([
            "additionalTransactions" => ["present", "array"],
            "additionalTransactions.*.id" => ["nullable", "integer", "distinct", "exists:report_additional_entries,id", new BelongsToReport($report)],
            "additionalTransactions.*.date" => ["required", "date"],
            "additionalTransactions.*.title" => ["required", "string", "max:64"],
            "additionalTransactions.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:1e7"],
            "additionalTransactions.*.price" => ["required", "numeric", "max:1e11", "min:-1e11", "not_in:1e11,-1e11"],
            "additionalTransactions.*.currency_id" => ["required", "integer", "exists:currencies,id"],
            "additionalTransactions.*.category_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount],
            "additionalTransactions.*.account_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount],
        ])["additionalTransactions"];

        $users = request()->validate([
            "users" => ["present", "array"],
            "users.*" => ["required", "email", "max:64", "distinct", "exists:users,email", "not_in:" . auth()->user()->email]
        ])["users"];

        // Update report
        $report->update([
            ...$data,
            "show_columns" => $this->getColumnBinary($columns)
        ]);

        // Save queries
        $report->queries()
            ->whereNotIn("id", array_column($queries, "id"))
            ->delete();

        foreach ($queries as $query) {
            if (isset($query["id"])) {
                $report->queries()->find($query["id"])->update($query);
            } else {
                $report->queries()->create($query);
            }
        }

        // Save additional entries
        $report->additionalEntries()
            ->whereNotIn("id", array_column($additionalTransactions, "id"))
            ->delete();

        foreach ($additionalTransactions as $transaction) {
            if (isset($transaction["id"])) {
                $report->additionalEntries()->find($transaction["id"])->update($transaction);
            } else {
                $report->additionalEntries()->create($transaction);
            }
        }

        // Save users
        $report->sharedUsers()
            ->whereNotIn("email", $users)
            ->detach();

        $report->sharedUsers()->attach(
            User::whereIn("email", $users)
                ->whereNotIn("email", $report->sharedUsers()->pluck("email"))
                ->pluck("id")
        );

        return response("");
    }
}
