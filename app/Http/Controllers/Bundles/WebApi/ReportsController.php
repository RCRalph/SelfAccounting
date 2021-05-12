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
        $queryTypes = ["income", "outcome"];

        $currencies = $this->getCurrencies();

        $categories = auth()->user()->categories
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id"]))
            ->groupBy("currency_id")
            ->toArray();

        $means = auth()->user()->meansOfPayment
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id"]))
            ->groupBy("currency_id")
            ->toArray();

        $titles = auth()->user()->income
            ->merge(auth()->user()->outcome)
            ->unique("title")
            ->values()
            ->map(fn ($item) => $item["title"])
            ->sort()
            ->values();

        $lastCurrency = $this->getLastCurrency();

        return response()->json(compact("currencies", "categories", "means", "titles", "queryTypes", "lastCurrency"));
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
            "additionalEntries.*.category_id" => ["present", "integer", new ValidCategoryMean("", false, "additionalEntries")],
            "additionalEntries.*.mean_id" => ["present", "integer", new ValidCategoryMean("", false, "additionalEntries")],

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
            $report->sharedUsers()->attach(User::firstWhere("email", $email));
        }

        return response()->json(["id" => $report->id]);
    }
}
