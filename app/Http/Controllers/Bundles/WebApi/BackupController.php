<?php

namespace App\Http\Controllers\Bundles\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Rules\BackupValidIODate;
use App\Rules\BackupValidCategoryMean;
use App\Rules\CorrectValueForCash;
use App\Rules\BackupValidMeanForCashMeans;
use App\Rules\BackupValidQueryDate;
use App\Rules\BackupValidQueryMinMax;
use App\Rules\BackupValidQueryCategoryMean;

use App\Backup;
use App\Currency;
use App\Income;
use App\Outcome;
use App\Category;
use App\MeanOfPayment;
use App\Bundle;
use App\Cash;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "bundle:backup"]);
    }

    public function index()
    {
        $backup = auth()->user()->backup;
        if (!$backup) {
            $backup = auth()->user()->backup()->create([
                "user_id" => auth()->user()->id,
                "last_backup" => now()->subDays(1),
                "last_restoration" => auth()->user()->created_at->addDays(30)
            ]);
        }

        $currencies = $this->getCurrencies();
        $canCreate = now()->subDays(1)->gte($backup->last_backup);
        $canRestore = now()->subDays(1)->gte($backup->last_restoration);

        // Check if user has bundles
        $bundles = auth()->user()->bundles
            ->merge(auth()->user()->premiumBundles);
        $hasBundles = [];

        foreach (Bundle::all() as $bundle) {
            $hasBundles[$bundle->code] = $bundles->contains($bundle);
        }

        $restoreDate = "";
        if (!$canRestore && auth()->user()->created_at->addDays(30)->eq($backup->last_restoration)) {
            $restoreDate = explode("T",
                Carbon::parse($backup->last_restoration)
                    ->addDays(1)->toISOString()
                )[0];
        }

        return response()->json(compact("currencies", "canCreate", "canRestore", "restoreDate", "hasBundles"));
    }

    public function createBackup()
    {
        $this->authorize("createBackup", Backup::class);

        // Check if user has bundles
        $bundles = auth()->user()->bundles
            ->merge(auth()->user()->premiumBundles);
        $hasBundles = [];

        foreach (Bundle::all() as $bundle) {
            $hasBundles[$bundle->code] = $bundles->contains($bundle);
        }

        // Gather categories
        $categories = auth()->user()->categories
            ->map(fn ($item) => collect($item)->except("user_id", "created_at", "updated_at"));

        if (!$hasBundles["charts"]) {
            foreach ($categories as $i => $category) {
                unset($categories[$i]["show_on_charts"]);
            }
        }

        $categoriesIDs = [];
        foreach ($categories as $i => $category) {
            $categoriesIDs[$category["id"]] = $i + 1;
            unset($category["id"]);
        }

        // Gather means of payment
        $means = auth()->user()->meansOfPayment
            ->map(function ($item) use ($hasBundles) {
                $item = collect($item)->except("user_id", "created_at", "updated_at");
                if (!$hasBundles["charts"]) {
                    unset($item["show_on_charts"]);
                }
                $item["first_entry_amount"] *= 1;
                return $item;
            });

        $meansIDs = [];
        foreach ($means as $i => $mean) {
            $meansIDs[$mean["id"]] = $i + 1;
            unset($mean["id"]);
        }

        // Gather income
        $income = auth()->user()->income
            ->map(function ($item) use ($categoriesIDs, $meansIDs) {
                $item = collect($item)->except("id", "user_id", "created_at", "updated_at");
                $item["amount"] *= 1;
                $item["price"] *= 1;
                $item["category_id"] = $item["category_id"] == null ? 0 : $categoriesIDs[$item["category_id"]];
                $item["mean_id"] = $item["mean_id"] == null ? 0 : $meansIDs[$item["mean_id"]];
                return $item;
            });

        // Gather outcome
        $outcome = auth()->user()->outcome
            ->map(function ($item) use ($categoriesIDs, $meansIDs) {
                $item = collect($item)->except("id", "user_id", "created_at", "updated_at");
                $item["amount"] *= 1;
                $item["price"] *= 1;
                $item["category_id"] = $item["category_id"] == null ? 0 : $categoriesIDs[$item["category_id"]];
                $item["mean_id"] = $item["mean_id"] == null ? 0 : $meansIDs[$item["mean_id"]];
                return $item;
            });

        $bundleData = [];

        // Gather cash
        if ($hasBundles["cashan"]) {
            $bundleData["cash"] = auth()->user()->cash
                ->map(fn ($item) => [
                        "currency_id" => $item->currency_id,
                        "value" => $item->value * 1,
                        "amount" => $item->pivot->amount
                    ]
                );

            $bundleData["cashMeans"] = [];
            foreach ($this->getCashMeans() as $currency => $mean) {
                if ($mean) {
                    array_push($bundleData["cashMeans"], [
                        "currency_id" => $currency,
                        "mean_id" => $meansIDs[$mean]
                    ]);
                }
            }
        }

        // Gather reports
        if ($hasBundles["report"]) {
            $bundleData["reports"] = [];
            $reports = auth()->user()->reports;

            foreach ($reports as $i => $report) {
                array_push(
                    $bundleData["reports"],
                    $report->only("title", "income_addition", "sort_dates_desc", "calculate_sum")
                );

                $bundleData["reports"][$i]["queries"] = [];
                $bundleData["reports"][$i]["additionalEntries"] = [];
                $bundleData["reports"][$i]["users"] = [];

                foreach ($report->queries as $query) {
                    unset($query["id"], $query["report_id"]);

                    $query["min_amount"] = $query["min_amount"] === null ? null : $query["min_amount"] * 1;
                    $query["max_amount"] = $query["max_amount"] === null ? null : $query["max_amount"] * 1;
                    $query["min_price"] = $query["min_price"] === null ? null : $query["min_price"] * 1;
                    $query["max_price"] = $query["max_price"] === null ? null : $query["max_price"] * 1;
                    $query["category_id"] = $query["category_id"] === null ? 0 : $categoriesIDs[$query["category_id"]];
                    $query["mean_id"] = $query["mean_id"] === null ? 0 : $meansIDs[$query["mean_id"]];

                    array_push($bundleData["reports"][$i]["queries"], $query->toArray());
                }

                foreach ($report->additionalEntries as $entry) {
                    unset($entry["id"], $entry["report_id"]);

                    $entry["amount"] *= 1;
                    $entry["price"] *= 1;
                    $entry["category_id"] = $entry["category_id"] === null ? 0 : $categoriesIDs[$entry["category_id"]];
                    $entry["mean_id"] = $entry["mean_id"] === null ? 0 : $meansIDs[$entry["mean_id"]];

                    array_push($bundleData["reports"][$i]["additionalEntries"], $entry->toArray());
                }

                foreach ($report->sharedUsers as $user) {
                    array_push($bundleData["reports"][$i]["users"], $user->email);
                }
            }
        }

        $backup = auth()->user()->backup
            ->update(["last_backup" => now()]);

        return response()->json(compact("categories", "means", "income", "outcome", "bundleData"));
    }

    public function restoreData()
    {
        $data = request()->validate([
            // Categories
            "categories.*.currency_id" => ["required", "exists:currencies,id"],
            "categories.*.name" => ["required", "string", "max:32"],
            "categories.*.income_category" => ["required", "boolean"],
            "categories.*.outcome_category" => ["required", "boolean"],
            "categories.*.count_to_summary" => ["required", "boolean"],
            "categories.*.show_on_charts" => ["nullable", "boolean"],
            "categories.*.start_date" => ["present", "nullable", "date"],
            "categories.*.end_date" => ["present", "nullable", "date", "after_or_equal:categories.*.start_date"],

            // Means
            "means.*.currency_id" => ["required", "exists:currencies,id"],
            "means.*.name" => ["required", "string", "max:32"],
            "means.*.income_mean" => ["required", "boolean"],
            "means.*.outcome_mean" => ["required", "boolean"],
            "means.*.count_to_summary" => ["required", "boolean"],
            "means.*.show_on_charts" => ["nullable", "boolean"],
            "means.*.first_entry_date" => ["required", "date"],
            "means.*.first_entry_amount" => ["required", "numeric", "max:1e11", "min:-1e11", "not_in:-1e11,1e11"],

            // Income
            "income.*.currency_id" => ["required", "exists:currencies,id"],
            "income.*.date" => ["required", "date", new BackupValidIODate("income")],
            "income.*.title" => ["required", "string", "max:64"],
            "income.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "income.*.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "income.*.category_id" => ["present", "nullable", "integer", new BackupValidCategoryMean("income")],
            "income.*.mean_id" => ["present", "nullable", "integer", new BackupValidCategoryMean("income")],

            // Outcome
            "outcome.*.currency_id" => ["required", "exists:currencies,id"],
            "outcome.*.date" => ["required", "date", new BackupValidIODate("outcome")],
            "outcome.*.title" => ["required", "string", "max:64"],
            "outcome.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "outcome.*.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "outcome.*.category_id" => ["present", "nullable", "integer", new BackupValidCategoryMean("outcome")],
            "outcome.*.mean_id" => ["present", "nullable", "integer", new BackupValidCategoryMean("outcome")],

            // Bundle data
            "bundleData" => ["present", "array"],

            // Cash
            "bundleData.cash" => ["nullable", "array"],
            "bundleData.cashMeans" => ["nullable", "array"],
            "bundleData.cash.*.currency_id" => ["required", "exists:currencies,id"],
            "bundleData.cash.*.value" => ["required", "numeric", "min:0", "max:1e8", "not_in:0,1e8", new CorrectValueForCash],
            "bundleData.cash.*.amount" => ["required", "integer", "min:1", "max:9223372036854775807"],

            // Cash means
            "bundleData.cashMeans.*.currency_id" => ["required", "distinct", "exists:currencies,id"],
            "bundleData.cashMeans.*.mean_id" => ["required", "integer", "min:1", new BackupValidMeanForCashMeans],

            // Reports
            "bundleData.reports" => ["nullable", "array"],
            "bundleData.reports.*.title" => ["required", "string", "max:64"],
            "bundleData.reports.*.income_addition" => ["required", "boolean"],
            "bundleData.reports.*.sort_dates_desc" => ["required", "boolean"],
            "bundleData.reports.*.calculate_sum" => ["required", "boolean"],

            // Report queries
            "bundleData.reports.*.queries" => ["present", "array"],
            "bundleData.reports.*.queries.*.query_data" => ["required", "string", "in:income,outcome"],
            "bundleData.reports.*.queries.*.min_date" => ["present", "nullable", "date", new BackupValidQueryDate],
            "bundleData.reports.*.queries.*.max_date" => ["present", "nullable", "date", new BackupValidQueryDate],
            "bundleData.reports.*.queries.*.title" => ["present", "nullable", "string", "max:64"],
            "bundleData.reports.*.queries.*.min_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:0,1e7", new BackupValidQueryMinMax("amount")],
            "bundleData.reports.*.queries.*.max_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:0,1e7", new BackupValidQueryMinMax("amount")],
            "bundleData.reports.*.queries.*.min_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:0,1e11", new BackupValidQueryMinMax("price")],
            "bundleData.reports.*.queries.*.max_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:0,1e11", new BackupValidQueryMinMax("price")],
            "bundleData.reports.*.queries.*.currency_id" => ["present", "nullable", "integer", "exists:currencies,id"],
            "bundleData.reports.*.queries.*.category_id" => ["present", "nullable", "integer", new BackupValidQueryCategoryMean("queries")],
            "bundleData.reports.*.queries.*.mean_id" => ["present", "nullable", "integer", new BackupValidQueryCategoryMean("queries")],

            "bundleData.reports.*.additionalEntries" => ["present", "array"],
            "bundleData.reports.*.additionalEntries.*.date" => ["required", "date"],
            "bundleData.reports.*.additionalEntries.*.title" => ["required", "string", "max:64"],
            "bundleData.reports.*.additionalEntries.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "bundleData.reports.*.additionalEntries.*.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "bundleData.reports.*.additionalEntries.*.currency_id" => ["required", "integer", "exists:currencies,id"],
            "bundleData.reports.*.additionalEntries.*.category_id" => ["present", "nullable", "integer", new BackupValidQueryCategoryMean("additionalEntries")],
            "bundleData.reports.*.additionalEntries.*.mean_id" => ["present", "nullable", "integer", new BackupValidQueryCategoryMean("additionalEntries")],

            "bundleData.reports.*.users" => ["present", "array"],
            "bundleData.reports.*.users.*" => ["required", "email", "max:64", "distinct", "exists:users,email", "not_in:" . auth()->user()->email]
        ]);

        dd($data);

        // Erase existing data
        auth()->user()->categories()->delete();
        auth()->user()->meansOfPayment()->delete();
        auth()->user()->income()->delete();
        auth()->user()->outcome()->delete();
        auth()->user()->cash()->detach();
        auth()->user()->cashMeans()->detach();

        // Enter categories and means
        $categories = [ 0 => null ]; $means = [ 0 => null ];
        foreach ($data["categories"] as $index => $category) {
            $categories[$index + 1] = auth()->user()->categories()
                ->create($category)->id;
        }

        foreach ($data["means"] as $index => $mean) {
            $means[$index + 1] = auth()->user()->meansOfPayment()
                ->create($mean)->id;
        }

        // Enter income and outcome
        foreach ($data["income"] as $income) {
            auth()->user()->income()->create(array_merge(
                $income,
                [
                    "category_id" => $categories[$income["category_id"]],
                    "mean_id" => $means[$income["mean_id"]]
                ]
            ));
        }

        foreach ($data["outcome"] as $outcome) {
            auth()->user()->outcome()->create(array_merge(
                $outcome,
                [
                    "category_id" => $categories[$outcome["category_id"]],
                    "mean_id" => $means[$outcome["mean_id"]]
                ]
            ));
        }

        // Enter cash data
        if (isset($data["bundleData"]["cash"])) {
            foreach ($data["bundleData"]["cash"] as $cash) {
                auth()->user()->cash()->attach(
                    Cash::firstWhere([
                        "currency_id" => $cash["currency_id"],
                        "value" => $cash["value"]
                    ]), [
                        "amount" => $cash["amount"]
                    ]
                );
            }
        }

        // Enter cash means
        if (isset($data["bundleData"]["cashMeans"])) {
            $meansToAttach = [];
            foreach ($data["bundleData"]["cashMeans"] as $cashMean) {
                array_push($meansToAttach, $means[$cashMean["mean_id"]]);
            }
            auth()->user()->cashMeans()->attach($meansToAttach);
        }

        auth()->user()->backup
            ->update(["last_restoration" => now()]);

        return response("", 200);
    }
}
