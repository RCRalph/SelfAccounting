<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Cash;
use App\User;

use App\Rules\Common\DateBeforeOrEqualField;
use App\Rules\Common\ValueLessOrEqualField;
use App\Rules\Extensions\Backup\CorrectDateIO;
use App\Rules\Extensions\Backup\ValidCategoryOrMean;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "extension:backup"]);
    }

    public function index()
    {
        // Get backup information (create on first visit)
        $backup = auth()->user()->backup;
        if (!$backup) {
            $backup = auth()->user()->backup()->create([
                "last_backup" => null,
                "last_restoration" => null
            ]);
        }

        $data = [
            "backup" => [
                "last" => $backup->last_backup,

                // Tooltip will be shown when creating a backup is disallowed, informing the user why they can't do that.
                // To disable the tooltip, set it's value to false.
                "tooltip" => (!$backup->last_backup || now()->subDays(1)->gte($backup->last_backup)) ?
                    false : "You can only create a backup once every 24 hours."
            ],
            "restore" => [
                "last" => $backup->last_restoration,

                // Tooltip will be shown when restoring the backup is disallowed, informing the user why they can't do that.
                // To disable the tooltip, set it's value to false.
                "tooltip" => (!$backup->last_restoration || now()->subDays(1)->gte($backup->last_restoration)) ?
                    false : "You can only restore a backup once every 24 hours."
            ]
        ];

        // Backup restoration is disabled until 30 days after account creation.
        // The reason is that the user gets 30 days of premium for free so they could theoretically never pay for premium but still have it.
        // The admin might allow restoration for particular users, hence the first condition.
        if (!$backup->last_restoration && now()->subDays(30)->lt(auth()->user()->created_at) && !env("APP_DEBUG")) {
            $data["restore"]["tooltip"] = "You can only restore a backup 30 days after creating an account.";
        }

        return response()->json(compact("data"));
    }

    public function create()
    {
        $this->authorize("create", Backup::class);

        // Get currency array as ID: ISO
        $currencies = $this->getCurrencies()
            ->mapWithKeys(fn ($item) => [$item["id"] => $item["ISO"]]);

        // Gather categories
        $categories = auth()->user()->categories()
            ->select("id", "currency_id AS currency", "name", "income_category", "outcome_category", "count_to_summary", "show_on_charts", "start_date", "end_date")
            ->orderBy("created_at")
            ->get();

        // $categoryIDs is an array which maps the id of a category to their index in the $categories array.// $categoryIDs is an array which maps the id of a category to their index in the $categories array.
        $categoryIDs = [];
        foreach ($categories as $i => $category) {
            $categories[$i]["currency"] = $currencies[$category["currency"]];

            $categoryIDs[$category["id"]] = $i + 1; // + 1 because the 0th category is considered as null
            unset($category["id"]);
        }

        // Gather means
        $means = auth()->user()->meansOfPayment()
            ->select("id", "currency_id AS currency", "name", "income_mean", "outcome_mean", "count_to_summary", "show_on_charts", "first_entry_date", "first_entry_amount")
            ->orderBy("created_at")
            ->get();

        // $meanIDs is an array which maps the id of a mean of payment to their index in the $means array.
        $meanIDs = [];
        foreach ($means as $i => $mean) {
            $means[$i]["currency"] = $currencies[$mean["currency"]];

            $meanIDs[$mean["id"]] = $i + 1; // + 1 because the 0th mean is considered as null
            unset($mean["id"]);
        }

        // Gather income
        $income = auth()->user()->income()
            ->select("date", "title", "amount", "price", "category_id", "mean_id", "currency_id AS currency")
            ->orderBy("date")
            ->get();

        foreach ($income as $i => $item) {
            $income[$i]["currency"] = $currencies[$item["currency"]];
            $income[$i]["category_id"] = !$item["category_id"] ? 0 : $categoryIDs[$item["category_id"]];
            $income[$i]["mean_id"] = !$item["mean_id"] ? 0 : $meanIDs[$item["mean_id"]];
        }

        // Gather outcome
        $outcome = auth()->user()->outcome()
            ->select("date", "title", "amount", "price", "category_id", "mean_id", "currency_id AS currency")
            ->orderBy("date")
            ->get();

        foreach ($outcome as $i => $item) {
            $outcome[$i]["currency"] = $currencies[$item["currency"]];
            $outcome[$i]["category_id"] = !$item["category_id"] ? 0 : $categoryIDs[$item["category_id"]];
            $outcome[$i]["mean_id"] = !$item["mean_id"] ? 0 : $meanIDs[$item["mean_id"]];
        }

        $extensions = [];

        // Gather cash handling data
        if (auth()->user()->extensionCodes->contains("cashan")) {
            $extensions["cashan"] = [
                "cash" => auth()->user()->cash()
                    ->select("currency_id AS currency", "value", "amount")
                    ->get()
                    ->makeHidden("pivot")
                    ->map(fn ($item) => [ ...$item->toArray(), "currency" => $currencies[$item["currency"]] ]),

                "means" => auth()->user()->cashMeans()
                    ->select("currency_id", "mean_id")
                    ->get()
                    ->map(fn ($item) => [
                        "currency" => $currencies[$item->currency_id],
                        "mean_id" => $meanIDs[$item->mean_id]
                    ])
            ];
        }

        // Gather report management data
        if (auth()->user()->extensionCodes->contains("report")) {
            $extensions["report"] = [
                "reports" => []
            ];

            foreach (auth()->user()->reports as $report) {
                array_push($extensions["report"]["reports"], [
                    ...$report->only("title", "income_addition", "sort_dates_desc", "calculate_sum", "show_columns"),

                    "queries" => $report->queries
                        ->makeHidden(["id", "report_id", "created_at", "updated_at"])
                        ->map(function ($item) use ($currencies, $categoryIDs, $meanIDs) {
                            $item["currency"] = $currencies[$item["currency_id"]] ?? null;
                            unset($item["currency_id"]);

                            if ($item["category_id"]) {
                                $item["category_id"] = $categoryIDs[$item["category_id"]];
                            }

                            if ($item["mean_id"]) {
                                $item["mean_id"] = $meanIDs[$item["mean_id"]];
                            }

                            return $item;
                        }),

                    "additionalEntries" => $report->additionalEntries
                        ->makeHidden(["id", "report_id", "created_at", "updated_at"])
                        ->map(function ($item) use ($currencies, $categoryIDs, $meanIDs) {
                            $item["currency"] = $currencies[$item["currency_id"]];
                            unset($item["currency_id"]);

                            if ($item["category_id"]) {
                                $item["category_id"] = $categoryIDs[$item["category_id"]];
                            }

                            if ($item["mean_id"]) {
                                $item["mean_id"] = $meanIDs[$item["mean_id"]];
                            }

                            return $item;
                        }),

                    "users" => $report->sharedUsers()->pluck("email")
                ]);
            }
        }

        // For debugging purposes disallowing backups for 24 hours is disabled.
        if (!env("APP_DEBUG")) {
            auth()->user()->backup()->update(["last_backup" => now()]);
        }

        return response()->json(compact("categories", "means", "income", "outcome", "extensions"));
    }

    public function restore()
    {
        $this->authorize("restore", Backup::class);

        // This function may take a long time because of its complexity, which required extending the max execution time.
        ini_set("max_execution_time", "300");

        $categories = request()->validate([
            "categories" => ["required", "array"],
            "categories.*.currency" => ["required", "exists:currencies,ISO"],
            "categories.*.name" => ["required", "string", "max:32"],
            "categories.*.income_category" => ["required", "boolean"],
            "categories.*.outcome_category" => ["required", "boolean"],
            "categories.*.count_to_summary" => ["required", "boolean"],
            "categories.*.show_on_charts" => ["required", "boolean"],
            "categories.*.start_date" => ["present", "nullable", "date", new DateBeforeOrEqualField("end_date")],
            "categories.*.end_date" => ["present", "nullable", "date"]
        ])["categories"];

        $means = request()->validate([
            "means" => ["required", "array"],
            "means.*.currency" => ["required", "exists:currencies,ISO"],
            "means.*.name" => ["required", "string", "max:32"],
            "means.*.income_mean" => ["required", "boolean"],
            "means.*.outcome_mean" => ["required", "boolean"],
            "means.*.count_to_summary" => ["required", "boolean"],
            "means.*.show_on_charts" => ["required", "boolean"],
            "means.*.first_entry_date" => ["required", "date", "after_or_equal:1970-01-01"],
            "means.*.first_entry_amount" => ["required", "numeric", "max:1e11", "min:-1e11", "not_in:-1e11,1e11"]
        ])["means"];

        $income = request()->validate([
            "income" => ["required", "array"],
            "income.*.date" => ["required", "date", "after_or_equal:1970-01-01", new CorrectDateIO($means)],
            "income.*.title" => ["required", "string", "max:64"],
            "income.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "income.*.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "income.*.currency" => ["required", "exists:currencies,ISO"],
            "income.*.category_id" => ["required", "integer", new ValidCategoryOrMean($categories, true)],
            "income.*.mean_id" => ["required", "integer", new ValidCategoryOrMean($means, true)]
        ])["income"];

        $outcome = request()->validate([
            "outcome" => ["required", "array"],
            "outcome.*.date" => ["required", "date", "after_or_equal:1970-01-01", new CorrectDateIO($means)],
            "outcome.*.title" => ["required", "string", "max:64"],
            "outcome.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "outcome.*.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "outcome.*.currency" => ["required", "exists:currencies,ISO"],
            "outcome.*.category_id" => ["required", "integer", new ValidCategoryOrMean($categories, true)],
            "outcome.*.mean_id" => ["required", "integer", new ValidCategoryOrMean($means, true)]
        ])["outcome"];

        // Delete all data from the account
        auth()->user()->categories()->delete();
        auth()->user()->meansOfPayment()->delete();
        auth()->user()->income()->delete();
        auth()->user()->outcome()->delete();

        // Get currency array as ID: ISO
        $currencies = $this->getCurrencies()
            ->mapWithKeys(fn ($item) => [$item["ISO"] => $item["id"]]);

        // $categoryIDs is an array which maps the index in the $categories array to the id of a category.
        $categoryIDs = [ 0 => null ];

        // Restore categories
        foreach ($categories as $category) {
            array_push($categoryIDs,
                auth()->user()->categories()->create([
                    ...$category,
                    "currency_id" => $currencies[$category["currency"]]
                ])->id
            );
        }

        // $meanIDs is an array which maps the index in the $means array to the id of a mean of payment.
        $meanIDs = [ 0 => null ];

        // Restore means of payment
        foreach ($means as $mean) {
            array_push($meanIDs,
                auth()->user()->meansOfPayment()->create([
                    ...$mean,
                    "currency_id" => $currencies[$mean["currency"]]
                ])->id
            );
        }

        // Restore income
        foreach ($income as $item) {
            auth()->user()->income()->create([
                ...$item,
                "currency_id" => $currencies[$item["currency"]],
                "category_id" => $categoryIDs[$item["category_id"]],
                "mean_id" => $meanIDs[$item["mean_id"]]
            ]);
        }

        // Restore outcome
        foreach ($outcome as $item) {
            auth()->user()->outcome()->create([
                ...$item,
                "currency_id" => $currencies[$item["currency"]],
                "category_id" => $categoryIDs[$item["category_id"]],
                "mean_id" => $meanIDs[$item["mean_id"]]
            ]);
        }

        // Restore extensions
        if (request()->has("extensions")) {
            request()->validate(["extensions" => ["required", "array"]]);

            // Restore cash handling
            if (request()->has("extensions.cashan") && auth()->user()->extensionCodes->contains("cashan")) {
                $extensionData = request()->validate([
                    "extensions.cashan" => ["required", "array"],

                    "extensions.cashan.cash" => ["required", "array"],
                    "extensions.cashan.cash.*.currency" => ["required", "exists:currencies,ISO"],
                    "extensions.cashan.cash.*.value" => ["required", "numeric", "min:0", "max:1e8", "not_in:0,1e8"],
                    "extensions.cashan.cash.*.amount" => ["required", "integer", "min:1", "max:1e7"],

                    "extensions.cashan.means" => ["required", "array"],
                    "extensions.cashan.means.*.currency" => ["required", "exists:currencies,ISO", "distinct"],
                    "extensions.cashan.means.*.mean_id" => ["required", "integer", new ValidCategoryOrMean($means)]
                ])["extensions"]["cashan"];

                // Delete account's cash data
                auth()->user()->cash()->detach();
                auth()->user()->cashMeans()->detach();

                // Restore cash
                foreach ($extensionData["cash"] as $item) {
                    $cashInDB = Cash::firstWhere([
                        "currency_id" => $currencies[$item["currency"]],
                        "value" => $item["value"]
                    ]);

                    if ($cashInDB && !auth()->user()->cash()->find($cashInDB)) {
                        auth()->user()->cash()->attach($cashInDB, ["amount" => $item["amount"]]);
                    }
                }

                // Restore cash means
                foreach ($extensionData["means"] as $item) {
                    auth()->user()->cashMeans()->attach($meanIDs[$item["mean_id"]]);
                }
            }

            // Restore report management
            if (request()->has("extensions.report") && auth()->user()->extensionCodes->contains("report")) {
                $extensionData = request()->validate([
                    "extensions.report.reports.*.title" => ["required", "string", "max:64"],
                    "extensions.report.reports.*.income_addition" => ["required", "boolean"],
                    "extensions.report.reports.*.sort_dates_desc" => ["required", "boolean"],
                    "extensions.report.reports.*.calculate_sum" => ["required", "boolean"],
                    "extensions.report.reports.*.show_columns" => ["required", "integer", "min:0", "max:127"],

                    "extensions.report.reports.*.queries" => ["present", "array"],
                    "extensions.report.reports.*.queries.*.query_data" => ["required", "string", "in:income,outcome"],
                    "extensions.report.reports.*.queries.*.min_date" => ["present", "nullable", "date", new DateBeforeOrEqualField("max_date")],
                    "extensions.report.reports.*.queries.*.max_date" => ["present", "nullable", "date"],
                    "extensions.report.reports.*.queries.*.title" => ["present", "nullable", "string", "max:64"],
                    "extensions.report.reports.*.queries.*.min_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:1e7", new ValueLessOrEqualField("max_amount")],
                    "extensions.report.reports.*.queries.*.max_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:1e7"],
                    "extensions.report.reports.*.queries.*.min_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:1e11", new ValueLessOrEqualField("max_price")],
                    "extensions.report.reports.*.queries.*.max_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:1e11"],
                    "extensions.report.reports.*.queries.*.currency" => ["present", "nullable", "exists:currencies,ISO"],
                    "extensions.report.reports.*.queries.*.category_id" => ["present", "nullable", "integer", new ValidCategoryOrMean($categories, "query_data")],
                    "extensions.report.reports.*.queries.*.mean_id" => ["present", "nullable", "integer", new ValidCategoryOrMean($means)],

                    "extensions.report.reports.*.additionalEntries" => ["present", "array"],
                    "extensions.report.reports.*.additionalEntries.*.date" => ["required", "date", "after_or_equal:1970-01-01"],
                    "extensions.report.reports.*.additionalEntries.*.title" => ["required", "string", "max:64"],
                    "extensions.report.reports.*.additionalEntries.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:1e7"],
                    "extensions.report.reports.*.additionalEntries.*.price" => ["required", "numeric",  "max:1e11", "min:-1e11", "not_in:1e11,-1e11"],
                    "extensions.report.reports.*.additionalEntries.*.currency" => ["required", "exists:currencies,ISO"],
                    "extensions.report.reports.*.additionalEntries.*.category_id" => ["present", "nullable", "integer", new ValidCategoryOrMean($categories)],
                    "extensions.report.reports.*.additionalEntries.*.mean_id" => ["present", "nullable", "integer", new ValidCategoryOrMean($means)],

                    "extensions.report.reports.*.users" => ["present", "array"],
                    "extensions.report.reports.*.users.*" => ["required", "email", "max:64", "not_in:" . auth()->user()->email]
                ])["extensions"]["report"];

                // Delete all account's reports
                auth()->user()->reports()->delete();

                // Restore reports
                foreach ($extensionData["reports"] as $report) {
                    $queries = $report["queries"];
                    $entries = $report["additionalEntries"];
                    $users = $report["users"];

                    unset($report["queries"], $report["additionalEntries"], $report["users"]);

                    // Restore report information
                    $reportInDB = auth()->user()->reports()->create($report);

                    // Restore report queries
                    foreach ($queries as $query) {
                        $currency = $query["currency"] ? $currencies[$query["currency"]] : null;
                        unset($query["currency"]);

                        $reportInDB->queries()->create([
                            ...$query,
                            "currency_id" => $currency,
                            "category_id" => $query["category_id"] ? $categoryIDs[$query["category_id"]] : null,
                            "mean_id" => $query["mean_id"] ? $meanIDs[$query["mean_id"]] : null
                        ]);
                    }

                    // Restore report additional entries
                    foreach ($entries as $entry) {
                        $currency = $entry["currency"] ? $currencies[$entry["currency"]] : null;
                        unset($entry["currency"]);

                        $reportInDB->additionalEntries()->create([
                            ...$entry,
                            "currency_id" => $currency,
                            "category_id" => $entry["category_id"] ? $categoryIDs[$entry["category_id"]] : null,
                            "mean_id" => $entry["mean_id"] ? $meanIDs[$entry["mean_id"]] : null
                        ]);
                    }

                    // Restore report shared users
                    foreach ($users as $user) {
                        $userInDB = User::firstWhere("email", $user);

                        if ($userInDB && !$reportInDB->sharedUsers()->find($cashInDB)) {
                            $reportInDB->sharedUsers()->attach($userInDB);
                        }
                    }
                }
            }
        }

        // For debugging purposes disallowing restorations for 24 hours is disabled.
        if (!env("APP_DEBUG")) {
            auth()->user()->backup()->update(["last_restoration" => now()]);
        }

        return response("");
    }
}
