<?php

namespace App\Http\Controllers\Bundles\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Rules\ValidQueryDate;
use App\Rules\ValidQueryMinMax;
use App\Rules\ValidCategoryMean;

use App\User;

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
            ->paginate(10);

        return response()->json(compact("reports"));
    }

    public function sharedReports()
    {
        $reports = auth()->user()->sharedReports()
            ->select("id", "title")
            ->paginate(10);

        return response()->json(compact("reports"));
    }

    public function create()
    {
        $queryTypes = ["income", "outcome"];

        $currencies = $this->getCurrencies();
        $nullArray = [
            "id" => 0,
            "name" => "N/A"
        ];

        $categories = auth()->user()->categories
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id"]))
            ->groupBy("currency_id")
            ->toArray();

        foreach ($currencies as $currency) {
            if (isset($categories[$currency["id"]])) {
                array_unshift($categories[$currency["id"]], $nullArray);
            }
            else {
                $categories[$currency["id"]] = [$nullArray];
            }
        }

        $means = auth()->user()->meansOfPayment
            ->map(fn ($item) => collect($item)->only(["id", "name", "currency_id"]))
            ->groupBy("currency_id")
            ->toArray();

        foreach ($currencies as $currency) {
            if (isset($means[$currency["id"]])) {
                array_unshift($means[$currency["id"]], $nullArray);
            }
            else {
                $means[$currency["id"]] = [$nullArray];
            }
        }


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



        dd($data);
    }
}
