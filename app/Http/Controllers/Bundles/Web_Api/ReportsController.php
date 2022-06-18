<?php

namespace App\Http\Controllers\Bundles\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Rules\ValidQueryDate;
use App\Rules\ValidQueryMinMax;
use App\Rules\ValidCategoryMean;
use App\Rules\BelongsToReport;

use App\User;
use App\Income;
use App\Outcome;
use App\Category;
use App\MeanOfPayment;
use App\Report;
use App\ReportQuery;
use App\ReportAdditionalEntry;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "bundle:report"]);
    }

    private function getReportCreationData()
    {
        $retArr = [];

        $retArr["queryTypes"] = ["income", "outcome"];

        $retArr["currencies"] = $this->getCurrencies();

        $retArr["categories"] = auth()->user()->categories
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id"]))
            ->groupBy("currency_id")
            ->toArray();

        $retArr["means"] = auth()->user()->meansOfPayment
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id"]))
            ->groupBy("currency_id")
            ->toArray();

        $retArr["titles"] = auth()->user()->income
            ->merge(auth()->user()->outcome)
            ->unique("title")
            ->values()
            ->map(fn ($item) => $item["title"])
            ->sort()
            ->values();

        $retArr["lastCurrency"] = $this->getLastCurrency();

        return $retArr;
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

    public function userReports()
    {
        $reports = auth()->user()->reports()
            ->select("id", "title")
            ->orderBy("id", "DESC")
            ->paginate(5);

        return response()->json(compact("reports"));
    }

    public function sharedReports()
    {
        $reports = auth()->user()->sharedReports()
            ->select("id", "title")
			->orderBy("report_id", "DESC")
            ->paginate(5);

        return response()->json(compact("reports"));
    }

    public function create()
    {
        return response()->json($this->getReportCreationData());
    }

    public function getUserInfo()
    {
        $userEmail = auth()->user()->email;
        $email = request()->validate([
            "email" => ["required", "email", "max:64", "exists:users,email", "not_in:$userEmail"]
        ])["email"];

        $user = User::firstWhere("email", $email)->only("username", "profile_picture");
        $user["profile_picture"] = $this->getProfilePictureLink($user["profile_picture"]);

        return response()->json($user);
    }

    public function store()
    {
        $data = request()->validate([
            "data" => ["required", "array"],
            "data.title" => ["required", "string", "max:64"],
            "data.income_addition" => ["required", "boolean"],
            "data.sort_dates_desc" => ["required", "boolean"],
            "data.calculate_sum" => ["required", "boolean"],

            "columns.*" => "boolean",
            "columns.date" => "required",
            "columns.title" => "required",
            "columns.amount" => "required",
            "columns.price" => "required",
            "columns.value" => "required",
            "columns.category" => "required",
            "columns.mean" => "required",

            "queries" => ["present", "array"],
            "queries.*.query_data" => ["required", "string", "in:income,outcome"],
            "queries.*.min_date" => ["present", "nullable", "date", new ValidQueryDate],
            "queries.*.max_date" => ["present", "nullable", "date", new ValidQueryDate],
            "queries.*.title" => ["present", "nullable", "string", "max:64"],
            "queries.*.min_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:0,1e7", new ValidQueryMinMax("amount")],
            "queries.*.max_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:0,1e7", new ValidQueryMinMax("amount")],
            "queries.*.min_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:0,1e11", new ValidQueryMinMax("price")],
            "queries.*.max_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:0,1e11", new ValidQueryMinMax("price")],
            "queries.*.currency_id" => ["present", "nullable", "integer", "exists:currencies,id"],
            "queries.*.category_id" => ["present", "nullable", "integer", new ValidCategoryMean("", false, "queries")],
            "queries.*.mean_id" => ["present", "nullable", "integer", new ValidCategoryMean("", false, "queries")],

            "additionalEntries" => ["present", "array"],
            "additionalEntries.*.date" => ["required", "date"],
            "additionalEntries.*.title" => ["required", "string", "max:64"],
            "additionalEntries.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "additionalEntries.*.price" => ["required", "numeric",  "max:1e11", "min:-1e11", "not_in:0,1e11,-1e11"],
            "additionalEntries.*.currency_id" => ["required", "integer", "exists:currencies,id"],
            "additionalEntries.*.category_id" => ["present", "nullable", "integer", new ValidCategoryMean("", false, "additionalEntries")],
            "additionalEntries.*.mean_id" => ["present", "nullable", "integer", new ValidCategoryMean("", false, "additionalEntries")],

            "users" => ["present", "array"],
            "users.*" => ["required", "email", "max:64", "distinct", "exists:users,email", "not_in:" . auth()->user()->email]
        ]);

        if (!$data["queries"] && !$data["additionalEntries"]) {
            abort(422, "No data given");
        }

        $data["data"]["show_columns"] = $this->getColumnBinary($data["columns"]);
        $report = auth()->user()->reports()->create($data["data"]);

        foreach ($data["queries"] as $query) {
            $report->queries()->create($query);
        }

        foreach ($data["additionalEntries"] as $entry) {
            $report->additionalEntries()->create($entry);
        }

        foreach ($data["users"] as $email) {
            $report->sharedUsers()->toggle(User::firstWhere("email", $email));
        }

        return response()->json(["id" => $report->id]);
    }

    public function edit(Report $report)
    {
        $this->authorize("update", $report);

        // Get report's data
        $data = $report->only("title", "income_addition", "sort_dates_desc", "calculate_sum");

        $columns = $this->getColumnsToShow($report->show_columns);

        $queries = $report->queries->toArray();
        foreach ($queries as $i => $query) {
            unset($queries[$i]["report_id"]);

            if ($queries[$i]["min_amount"] !== null) {
                $queries[$i]["min_amount"] *= 1;
            }

            if ($queries[$i]["max_amount"] !== null) {
                $queries[$i]["max_amount"] *= 1;
            }

            if ($queries[$i]["min_price"] !== null) {
                $queries[$i]["min_price"] *= 1;
            }

            if ($queries[$i]["max_price"] !== null) {
                $queries[$i]["max_price"] *= 1;
            }
        }

        $additionalEntries = $report->additionalEntries->toArray();
        foreach ($additionalEntries as $i => $entry) {
            unset($additionalEntries[$i]["report_id"]);
            $additionalEntries[$i]["amount"] *= 1;
            $additionalEntries[$i]["price"] *= 1;
        }

        $users = $report->sharedUsers->map(fn ($item) => $item->only("username", "email", "profile_picture"))->toArray();
        foreach ($users as $i => $user) {
            $users[$i]["profile_picture"] = $this->getProfilePictureLink($user["profile_picture"]);
        }

        $reportData = $this->getReportCreationData();

        return response()->json(array_merge(
            compact("data", "queries", "additionalEntries", "users", "columns"),
            $reportData
        ));
    }

    public function update(Report $report)
    {
        $this->authorize("update", $report);

        $data = request()->validate([
            "data" => ["required", "array"],
            "data.title" => ["required", "string", "max:64"],
            "data.income_addition" => ["required", "boolean"],
            "data.sort_dates_desc" => ["required", "boolean"],
            "data.calculate_sum" => ["required", "boolean"],

            "columns.*" => "boolean",
            "columns.date" => "required",
            "columns.title" => "required",
            "columns.amount" => "required",
            "columns.price" => "required",
            "columns.value" => "required",
            "columns.category" => "required",
            "columns.mean" => "required",

            "queries" => ["present", "array"],
            "queries.*.id" => ["present", "nullable", "integer", "exists:report_queries,id", new BelongsToReport($report)],
            "queries.*.query_data" => ["required", "string", "in:income,outcome"],
            "queries.*.min_date" => ["present", "nullable", "date", new ValidQueryDate],
            "queries.*.max_date" => ["present", "nullable", "date", new ValidQueryDate],
            "queries.*.title" => ["present", "nullable", "string", "max:64"],
            "queries.*.min_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:0,1e7", new ValidQueryMinMax("amount")],
            "queries.*.max_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:0,1e7", new ValidQueryMinMax("amount")],
            "queries.*.min_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:0,1e11", new ValidQueryMinMax("price")],
            "queries.*.max_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:0,1e11", new ValidQueryMinMax("price")],
            "queries.*.currency_id" => ["present", "nullable", "integer", "exists:currencies,id"],
            "queries.*.category_id" => ["present", "nullable", "integer", new ValidCategoryMean("", false, "queries")],
            "queries.*.mean_id" => ["present", "nullable", "integer", new ValidCategoryMean("", false, "queries")],

            "additionalEntries" => ["present", "array"],
            "additionalEntries.*.id" => ["present", "nullable", "integer", "exists:report_additional_entries,id", new BelongsToReport($report)],
            "additionalEntries.*.date" => ["required", "date"],
            "additionalEntries.*.title" => ["required", "string", "max:64"],
            "additionalEntries.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "additionalEntries.*.price" => ["required", "numeric", "max:1e11", "min:-1e11", "not_in:0,1e11,-1e11"],
            "additionalEntries.*.currency_id" => ["required", "integer", "exists:currencies,id"],
            "additionalEntries.*.category_id" => ["present", "nullable", "integer", new ValidCategoryMean("", false, "additionalEntries")],
            "additionalEntries.*.mean_id" => ["present", "nullable", "integer", new ValidCategoryMean("", false, "additionalEntries")],

            "users" => ["present", "array"],
            "users.*" => ["required", "email", "max:64", "distinct", "exists:users,email", "not_in:" . auth()->user()->email]
        ]);

        if (!$data["queries"] && !$data["additionalEntries"]) {
            abort(422, "No data given");
        }

        $data["data"]["show_columns"] = $this->getColumnBinary($data["columns"]);
        $report->update($data["data"]);
        unset($data["data"]["show_columns"]);

        // Handle queries
        $IDsFromDB = $report->queries
            ->map(fn ($item) => $item->id)
            ->toArray();
        $IDsFromData = array_map(fn ($item) => $item["id"], $data["queries"]);

        $report->queries()->whereIn("id", array_diff($IDsFromDB, $IDsFromData))->delete();

        foreach ($data["queries"] as $i => $query) {
            if (!$query["id"]) {
                unset($query["id"]);
                $data["queries"][$i]["id"] = $report->queries()->create($query)->id;
            }
            else {
                $report->queries()->where("id", $query["id"])->update($query);
            }
        }

        // Handle additional entries
        $IDsFromDB = $report->additionalEntries
            ->map(fn ($item) => $item->id)
            ->toArray();
        $IDsFromData = array_map(fn ($item) => $item["id"], $data["additionalEntries"]);

        $report->additionalEntries()->whereIn("id", array_diff($IDsFromDB, $IDsFromData))->delete();
        foreach ($data["additionalEntries"] as $i => $entry) {
            if (!$entry["id"]) {
                unset($entry["id"]);
                $data["additionalEntries"][$i]["id"] = $report->additionalEntries()->create($entry)->id;
            }
            else {
                $report->additionalEntries()->where("id", $entry["id"])->update($entry);
            }
        }

        // Handle users
        $usersFromDB = $report->sharedUsers
            ->map(fn ($item) => $item->email)
            ->toArray();

        if (array_diff($usersFromDB, $data["users"])) {
            $report->sharedUsers()->whereIn("email", array_diff($usersFromDB, $data["users"]))->detach();
        }
        foreach ($data["users"] as $email) {
            if (!$report->sharedUsers->where("email", $email)->count()) {
                $report->sharedUsers()->attach(User::firstWhere("email", $email));
            }
        }

        return response()->json($data);
    }

    public function destroy(Report $report)
    {
        $this->authorize("update", $report);

        $report->delete();

        return response("");
    }

    public function show(Report $report)
    {
        $this->authorize("view", $report);

        $reportData = [
            "title" => $report->title,
            "columns" => $this->getColumnsToShow($report->show_columns)
        ];

        $currencies = $this->getCurrencies();

        $categories = Category::where("user_id", $report->user_id)
            ->select("id", "name", "currency_id")
            ->get()
            ->groupBy("currency_id");

        $means = MeanOfPayment::where("user_id", $report->user_id)
            ->select("id", "name", "currency_id")
            ->get()
            ->groupBy("currency_id");

        $rows = collect();
        foreach ($report->queries as $query) {
            $data = $query->query_data == "income" ?
                Income::where("user_id", $report->user_id) :
                Outcome::where("user_id", $report->user_id);

            $queryFieldsToCheck = [
                "min_date", "max_date", "title", "min_amount", "max_amount",
                "min_price", "max_price", "currency_id", "category_id", "mean_id"
            ];

            foreach ($queryFieldsToCheck as $field) {
                if ($query[$field]) {
                    $firstLetters = substr($field, 0, 4);
                    $isMin = $firstLetters == "min_";
                    $isMax = $firstLetters == "max_";

                    $data->where(
                        ($isMin || $isMax) ? substr($field, 4, strlen($field) - 4) : $field,
                        $isMin ? ">=" : ($isMax ? "<=" : "="),
                        $query[$field]
                    );
                }
            }

            $rows = $rows->merge($data
                ->select("date", "title", "amount", "price", "currency_id", "mean_id", "category_id")
                ->get()
				->map(function ($item) use ($report, $query) {
					$item->amount *= ($report->income_addition xor $query->query_data == "income") ? -1 : 1;
					$item->price *= 1;
                    $item->value = round($item->amount * $item->price, 2);
					return $item;
				})
            );
        }

        foreach ($report->additionalEntries as $entry) {
            unset($entry["id"], $entry["report_id"]);

            $entry->amount *= 1;
            $entry->price *= 1;
            $entry->value = round($entry->amount * $entry->price, 2);
            $rows = $rows->push($entry);
        }

        $rows = $rows->sort(function ($a, $b) use ($report) {
			if (strtotime($a->date) == strtotime($b->date)) {
				if ($a->title === $b->title) {
					return 0;
				}

				return strcasecmp($a->title, $b->title);
			}

			return (strtotime($a->date) < strtotime($b->date) ? -1 : 1) * ($report->sort_dates_desc ? -1 : 1);
		})->unique()->values();

        $sum = null;
        if ($report->calculate_sum) {
            $sum = [];
            foreach ($rows as $row) {
                if (isset($sum[$row["currency_id"]])) {
                    $sum[$row["currency_id"]] += $row["amount"] * $row["price"];
                }
                else {
                    $sum[$row["currency_id"]] = $row["amount"] * $row["price"];
                }
            }

            foreach ($sum as $i => $s) {
                $sum[$i] = round($sum[$i], 2);
            }
        }

        $columnToKey = [
            "date" => "date",
            "title" => "title",
            "amount" => "amount",
            "value" => "value",
            "price" => "price",
            "category" => "category_id",
            "mean" => "mean_id"
        ];

        foreach ($reportData["columns"] as $column => $toShow) {
            if (!$toShow) {
                foreach ($rows as $i => $row) {
                    unset($row[$columnToKey[$column]]);
                }
            }
        }

        $columnNames = $this->TABLE_HEAD;

        return response()->json(compact("reportData", "rows", "currencies", "categories", "means", "sum", "columnNames"));
    }

    public function duplicate(Report $report)
    {
        $this->authorize("update", $report);

        $reportData = $report->toArray();
        unset($reportData["id"], $reportData["created_at"], $reportData["updated_at"]);

        $new = Report::create($reportData);

        foreach ($report->queries->toArray() as $query) {
            unset($query["id"], $query["report_id"]);

            $new->queries()->create($query);
        }

        foreach ($report->additionalEntries->toArray() as $additionalEntry) {
            unset($additionalEntry["id"], $additionalEntry["report_id"]);

            $new->additionalEntries()->create($additionalEntry);
        }

        foreach ($report->sharedUsers as $user) {
            $new->sharedUsers()->attach($user);
        }

        return response()->json([ "id" => $new["id"] ]);
    }
}
