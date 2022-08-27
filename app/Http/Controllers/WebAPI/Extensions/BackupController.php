<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "extension:backup"]);
    }

    public function index()
    {
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
                "tooltip" => (!$backup->last_backup || now()->subDays(1)->gte($backup->last_backup)) ?
                    false : "You can only create a backup once every 24 hours."
            ],
            "restore" => [
                "last" => $backup->last_restoration,
                "tooltip" => (!$backup->last_restoration || now()->subDays(1)->gte($backup->last_restoration)) ?
                    false : "You can only restore a backup once every 24 hours."
            ]
        ];

        if (!$backup->last_restoration && now()->subDays(7)->lt(auth()->user()->created_at)) {
            $data["restore"]["tooltip"] = "You can only restore a backup 7 days after creating an account.";
        }

        return response()->json(compact("data"));
    }

    public function create()
    {
        $this->authorize("create", Backup::class);

        // Gather categories
        $categories = auth()->user()->categories()
            ->select("id", "currency_id", "name", "income_category", "outcome_category", "count_to_summary", "show_on_charts", "start_date", "end_date")
            ->orderBy("created_at")
            ->get();

        $categoryIDs = [];
        foreach ($categories as $i => $category) {
            $categoryIDs[$category["id"]] = $i + 1;
            unset($category["id"]);
        }

        // Gather means
        $means = auth()->user()->meansOfPayment()
            ->select("id", "currency_id", "name", "income_mean", "outcome_mean", "count_to_summary", "show_on_charts", "first_entry_date", "first_entry_amount")
            ->orderBy("created_at")
            ->get();

        $meanIDs = [];
        foreach ($means as $i => $mean) {
            $meanIDs[$mean["id"]] = $i + 1;
            unset($mean["id"]);
        }

        // Gather income
        $income = auth()->user()->income()
            ->select("date", "title", "amount", "price", "category_id", "mean_id", "currency_id")
            ->orderBy("date")
            ->get();

        foreach ($income as $i => $item) {
            $income[$i]["category_id"] = !$item["category_id"] ? 0 : $categoryIDs[$item["category_id"]];
            $income[$i]["mean_id"] = !$item["mean_id"] ? 0 : $meanIDs[$item["mean_id"]];
        }

        // Gather outcome
        $outcome = auth()->user()->outcome()
            ->select("date", "title", "amount", "price", "category_id", "mean_id", "currency_id")
            ->orderBy("date")
            ->get();

        foreach ($outcome as $i => $item) {
            $outcome[$i]["category_id"] = !$item["category_id"] ? 0 : $categoryIDs[$item["category_id"]];
            $outcome[$i]["mean_id"] = !$item["mean_id"] ? 0 : $meanIDs[$item["mean_id"]];
        }

        $extensions = [];

        if (auth()->user()->extensionCodes->contains("cashan")) {
            $extensions["cashan"] = [
                "cash" => auth()->user()->cash()
                    ->select("currency_id", "value", "amount")
                    ->get()
                    ->makeHidden("pivot"),

                "means" => auth()->user()->cashMeans()
                    ->select("currency_id", "mean_id")
                    ->get()
                    ->map(fn ($item) => [
                        "currency_id" => $item->currency_id,
                        "mean_id" => $meanIDs[$item->mean_id]
                    ])
            ];
        }

        if (auth()->user()->extensionCodes->contains("report")) {
            $extensions["report"] = [
                "reports" => []
            ];

            foreach (auth()->user()->reports as $report) {
                array_push($extensions["report"]["reports"], [
                    ...$report->only("title", "income_addition", "sort_dates_desc", "calculate_sum", "show_columns"),

                    "queries" => $report->queries
                        ->makeHidden("id", "report_id", "created_at", "updated_at")
                        ->map(function ($item) {
                            if ($item["category_id"]) {
                                $item["category_id"] = $categoryIDs[$item["category_id"]];
                            }

                            if ($item["mean_id"]) {
                                $item["mean_id"] = $meanIDs[$item["mean_id"]];
                            }

                            return $item;
                        }),

                    "additionalEntries" => $report->additionalEntries
                        ->makeHidden("id", "report_id", "created_at", "updated_at")
                        ->map(function ($item) {
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

        if (!env("APP_DEBUG")) {
            $backup = auth()->user()->backup()->update(["last_backup" => now()]);
        }

        return response()->json(compact("categories", "means", "income", "outcome", "extensions"));
    }
}
