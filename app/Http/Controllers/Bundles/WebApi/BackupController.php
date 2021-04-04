<?php

namespace App\Http\Controllers\Bundles\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Rules\BackupValidIODate;
use App\Rules\BackupValidCategoryMean;

use App\Backup;
use App\Currency;
use App\Income;
use App\Outcome;
use App\Category;
use App\MeanOfPayment;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "bundle:backup"]);
    }

    public function index()
    {
        if (!auth()->user()->backup) {
            Backup::insert([
                "user_id" => auth()->user()->id,
                "last_backup" => now()->subDays(1),
                "last_restoration" => now()->subDays(1)
            ]);

            return response()->json([
                "canCreate" => true,
                "canRestore" => true
            ]);
        }
        $currencies = Currency::all();

        $lastBackup = Carbon::parse(auth()->user()->backup->last_backup);
        $lastRestoration = Carbon::parse(auth()->user()->backup->last_restoration);

        return response()->json(array_merge(
            compact("currencies"),
            [
                "canCreate" => now()->subDays(1)->gte($lastBackup),
                "canRestore" => now()->subDays(1)->gte($lastRestoration)
            ]
        ));
    }

    public function createBackup()
    {
        $this->authorize("createBackup", Backup::class);

        // Gather categories
        $categories = auth()->user()->categories
            ->map(fn ($item) => collect($item)->except("user_id", "created_at", "updated_at"));
        $categoriesIDs = [];
        foreach ($categories as $i => $category) {
            $categoriesIDs[$category["id"]] = $i + 1;
            unset($category["id"]);
        }

        // Gather means of payment
        $means = auth()->user()->meansOfPayment
            ->map(function ($item) {
                $item = collect($item)->except("user_id", "created_at", "updated_at");
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

        $outcome = auth()->user()->outcome
            ->map(function ($item) use ($categoriesIDs, $meansIDs) {
                $item = collect($item)->except("id", "user_id", "created_at", "updated_at");
                $item["amount"] *= 1;
                $item["price"] *= 1;
                $item["category_id"] = $item["category_id"] == null ? 0 : $categoriesIDs[$item["category_id"]];
                $item["mean_id"] = $item["mean_id"] == null ? 0 : $meansIDs[$item["mean_id"]];
                return $item;
            });

        $backup = auth()->user()->backup
            ->update(["last_backup" => now()]);

        return response()->json(compact("categories", "means", "income", "outcome"));
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
            "categories.*.show_on_charts" => ["required", "boolean"],
            "categories.*.start_date" => ["present", "nullable", "date"],
            "categories.*.end_date" => ["present", "nullable", "date", "after_or_equal:categories.*.start_date"],

            // Means
            "means.*.currency_id" => ["required", "exists:currencies,id"],
            "means.*.name" => ["required", "string", "max:32"],
            "means.*.income_mean" => ["required", "boolean"],
            "means.*.outcome_mean" => ["required", "boolean"],
            "means.*.count_to_summary" => ["required", "boolean"],
            "means.*.show_on_charts" => ["required", "boolean"],
            "means.*.first_entry_date" => ["required", "date"],
            "means.*.first_entry_amount" => ["required", "numeric", "max:1e11", "min:-1e11", "not_in:-1e11,1e11"],

            // Income
            "income.*.currency_id" => ["required", "exists:currencies,id"],
            "income.*.date" => ["required", "date", new BackupValidIODate("income")],
            "income.*.title" => ["required", "string", "max:64"],
            "income.*.amount" => ["required", "numeric", "max:1e6", "min:0", "not_in:0,1e6"],
            "income.*.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "income.*.category_id" => ["present", "integer", new BackupValidCategoryMean("income")],
            "income.*.mean_id" => ["present", "integer", new BackupValidCategoryMean("income")],

            // Outcome
            "outcome.*.currency_id" => ["required", "exists:currencies,id"],
            "outcome.*.date" => ["required", "date", new BackupValidIODate("outcome")],
            "outcome.*.title" => ["required", "string", "max:64"],
            "outcome.*.amount" => ["required", "numeric", "max:1e6", "min:0", "not_in:0,1e6"],
            "outcome.*.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "outcome.*.category_id" => ["present", "integer", new BackupValidCategoryMean("outcome")],
            "outcome.*.mean_id" => ["present", "integer", new BackupValidCategoryMean("outcome")]
        ]);

        // Erase existing data
        auth()->user()->categories()->delete();
        auth()->user()->meansOfPayment()->delete();
        auth()->user()->income()->delete();
        auth()->user()->outcome()->delete();

        // Enter categories and means
        $categories = [ 0 => null ]; $means = [ 0 => null ];
        foreach ($data["categories"] as $index => $category) {
            $categories[$index + 1] = Category::create(array_merge(
                ["user_id" => auth()->user()->id],
                $category
            ))->id;
        }

        foreach ($data["means"] as $index => $mean) {
            $means[$index + 1] = MeanOfPayment::create(array_merge(
                ["user_id" => auth()->user()->id],
                $mean
            ))->id;
        }

        // Enter income and outcome
        foreach ($data["income"] as $income) {
            Income::create(array_merge(
                $income,
                [
                    "user_id" => auth()->user()->id,
                    "category_id" => $categories[$income["category_id"]],
                    "mean_id" => $means[$income["mean_id"]]
                ]
            ));
        }

        foreach ($data["outcome"] as $outcome) {
            Outcome::create(array_merge(
                $outcome,
                [
                    "user_id" => auth()->user()->id,
                    "category_id" => $categories[$outcome["category_id"]],
                    "mean_id" => $means[$outcome["mean_id"]]
                ]
            ));
        }

        auth()->user()->backup
            ->update(["last_restoration" => now()]);

        return response("", 200);
    }
}
