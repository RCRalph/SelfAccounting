<?php

namespace App\Http\Controllers\Bundles\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Rules\ValidQueryDate;
use App\Rules\ValidQueryMinMax;
use App\Rules\ValidCategoryMean;

use App\User;
use App\Report;

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

    public function userReports()
    {
        $reports = auth()->user()->reports()
            ->select("id", "title")
            ->orderBy("id")
            ->paginate(5);

        return response()->json(compact("reports"));
    }

    public function sharedReports()
    {
        $reports = auth()->user()->sharedReports()
            ->select("id", "title")
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

            "queries" => ["present", "array"],
            "queries.*.query_data" => ["required", "string", "in:income,outcome"],
            "queries.*.min_date" => ["present", "nullable", "date", new ValidQueryDate],
            "queries.*.max_date" => ["present", "nullable", "date", new ValidQueryDate],
            "queries.*.title" => ["present", "nullable", "string", "max:64"],
            "queries.*.min_amount" => ["present", "nullable", "numeric", "max:1e6", "min:0", "not_in:0,1e6", new ValidQueryMinMax("amount")],
            "queries.*.max_amount" => ["present", "nullable", "numeric", "max:1e6", "min:0", "not_in:0,1e6", new ValidQueryMinMax("amount")],
            "queries.*.min_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:0,1e11", new ValidQueryMinMax("price")],
            "queries.*.max_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:0,1e11", new ValidQueryMinMax("price")],
            "queries.*.currency_id" => ["present", "nullable", "integer", "exists:currencies,id"],
            "queries.*.category_id" => ["present", "nullable", "integer", new ValidCategoryMean("", false, "queries")],
            "queries.*.mean_id" => ["present", "nullable", "integer", new ValidCategoryMean("", false, "queries")],

            "additionalEntries" => ["present", "array"],
            "additionalEntries.*.date" => ["required", "date"],
            "additionalEntries.*.title" => ["required", "string", "max:64"],
            "additionalEntries.*.amount" => ["required", "numeric", "max:1e6", "min:0", "not_in:0,1e6"],
            "additionalEntries.*.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "additionalEntries.*.currency_id" => ["required", "integer", "exists:currencies,id"],
            "additionalEntries.*.category_id" => ["present", "nullable", "integer", new ValidCategoryMean("", false, "additionalEntries")],
            "additionalEntries.*.mean_id" => ["present", "nullable", "integer", new ValidCategoryMean("", false, "additionalEntries")],

            "users" => ["present", "array"],
            "users.*" => ["required", "string", "max:64", "distinct", "exists:users,email"]
        ]);

        if (!$data["queries"] && !$data["additionalEntries"]) {
            abort(422, "No data given");
        }

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

    public function edit(Report $report) {
        $this->authorize("update", $report);

        // Get report's data
        $data = $report->only("title", "income_addition", "sort_dates_desc");

        $queries = $report->queries->toArray();
        foreach ($queries as $i => $query) {
            unset($queries[$i]["id"], $queries[$i]["report_id"]);

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
            unset($additionalEntries[$i]["id"], $additionalEntries[$i]["report_id"]);
            $additionalEntries[$i]["amount"] *= 1;
            $additionalEntries[$i]["price"] *= 1;
        }

        $users = $report->sharedUsers->map(fn ($item) => $item->only("username", "email", "profile_picture"))->toArray();
        foreach ($users as $i => $user) {
            $users[$i]["profile_picture"] = $this->getProfilePictureLink($user["profile_picture"]);
        }

        $reportData =  $this->getReportCreationData();

        return response()->json(array_merge(
            compact("data", "queries", "additionalEntries", "users"),
            $reportData
        ));
    }
}
