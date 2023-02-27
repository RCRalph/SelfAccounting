<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Cash;
use App\Models\User;

use App\Rules\Common\DateBeforeOrEqualField;
use App\Rules\Common\ValueLessOrEqualField;
use App\Rules\Extensions\Backup\CorrectDateIncomeExpences;
use App\Rules\Extensions\Backup\CorrectTransferDate;
use App\Rules\Extensions\Backup\ValidCategoryOrAccount;
use App\Rules\Extensions\Backup\ValidTransferAccount;

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
            ->select("id", "currency_id AS currency", "icon", "name", "used_in_income", "used_in_expences", "count_to_summary", "show_on_charts", "start_date", "end_date")
            ->orderBy("created_at")
            ->get();

        // $categoryIDs is an array which maps the id of a category to their index in the $categories array.// $categoryIDs is an array which maps the id of a category to their index in the $categories array.
        $categoryIDs = [];
        foreach ($categories as $i => $category) {
            $categories[$i]["currency"] = $currencies[$category["currency"]];

            $categoryIDs[$category["id"]] = $i + 1; // + 1 because the 0th category is considered as null
            unset($category["id"]);
        }

        // Gather accounts
        $accounts = auth()->user()->accounts()
            ->select("id", "currency_id AS currency", "icon", "name", "used_in_income", "used_in_expences", "count_to_summary", "show_on_charts", "start_date", "start_balance")
            ->orderBy("created_at")
            ->get();

        // $accountIDs is an array which maps the id of a account to their index in the $accounts array.
        $accountIDs = [];
        foreach ($accounts as $i => $account) {
            $accounts[$i]["currency"] = $currencies[$account["currency"]];

            $accountIDs[$account["id"]] = $i + 1; // + 1 because the 0th account is considered as null
            unset($account["id"]);
        }

        // Gather income
        $income = auth()->user()->income()
            ->select("date", "title", "amount", "price", "category_id", "account_id", "currency_id AS currency")
            ->orderBy("date")
            ->get();

        foreach ($income as $i => $item) {
            $income[$i]["currency"] = $currencies[$item["currency"]];
            $income[$i]["category_id"] = !$item["category_id"] ? 0 : $categoryIDs[$item["category_id"]];
            $income[$i]["account_id"] = !$item["account_id"] ? 0 : $accountIDs[$item["account_id"]];
        }

        // Gather expences
        $expences = auth()->user()->expences()
            ->select("date", "title", "amount", "price", "category_id", "account_id", "currency_id AS currency")
            ->orderBy("date")
            ->get();

        foreach ($expences as $i => $item) {
            $expences[$i]["currency"] = $currencies[$item["currency"]];
            $expences[$i]["category_id"] = !$item["category_id"] ? 0 : $categoryIDs[$item["category_id"]];
            $expences[$i]["account_id"] = !$item["account_id"] ? 0 : $accountIDs[$item["account_id"]];
        }

        // Gather transfers
        $transfers = auth()->user()->transfers()
            ->select("date", "source_account_id", "source_value", "target_account_id", "target_value")
            ->orderBy("date")
            ->get();

        foreach ($transfers as $i => $item) {
            $transfers[$i]["source_account_id"] = $accountIDs[$item["source_account_id"]];
            $transfers[$i]["target_account_id"] = $accountIDs[$item["target_account_id"]];
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

                "accounts" => auth()->user()->cashAccounts()
                    ->select("currency_id", "account_id")
                    ->get()
                    ->map(fn ($item) => [
                        "currency" => $currencies[$item->currency_id],
                        "account_id" => $accountIDs[$item->account_id]
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
                        ->map(function ($item) use ($currencies, $categoryIDs, $accountIDs) {
                            $item["currency"] = $currencies[$item["currency_id"]] ?? null;
                            unset($item["currency_id"]);

                            if ($item["category_id"]) {
                                $item["category_id"] = $categoryIDs[$item["category_id"]];
                            }

                            if ($item["account_id"]) {
                                $item["account_id"] = $accountIDs[$item["account_id"]];
                            }

                            return $item;
                        }),

                    "additionalEntries" => $report->additionalEntries
                        ->makeHidden(["id", "report_id", "created_at", "updated_at"])
                        ->map(function ($item) use ($currencies, $categoryIDs, $accountIDs) {
                            $item["currency"] = $currencies[$item["currency_id"]];
                            unset($item["currency_id"]);

                            if ($item["category_id"]) {
                                $item["category_id"] = $categoryIDs[$item["category_id"]];
                            }

                            if ($item["account_id"]) {
                                $item["account_id"] = $accountIDs[$item["account_id"]];
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

        return response()->json(compact("categories", "accounts", "income", "expences", "transfers", "extensions"));
    }

    public function restore()
    {
        $this->authorize("restore", Backup::class);

        // This function may take a long time because of its complexity, which required extending the max execution time.
        ini_set("max_execution_time", "300");

        $categories = request()->validate([
            "categories" => ["present", "array"],
            "categories.*.currency" => ["required", "exists:currencies,ISO"],
            "categories.*.icon" => ["present", "string", "max:64"],
            "categories.*.name" => ["required", "string", "max:32"],
            "categories.*.used_in_income" => ["required", "boolean"],
            "categories.*.used_in_expences" => ["required", "boolean"],
            "categories.*.count_to_summary" => ["required", "boolean"],
            "categories.*.show_on_charts" => ["required", "boolean"],
            "categories.*.start_date" => ["present", "nullable", "date", new DateBeforeOrEqualField("end_date")],
            "categories.*.end_date" => ["present", "nullable", "date"]
        ])["categories"];

        $accounts = request()->validate([
            "accounts" => ["present", "array"],
            "accounts.*.currency" => ["required", "exists:currencies,ISO"],
            "accounts.*.icon" => ["present", "string", "max:64"],
            "accounts.*.name" => ["required", "string", "max:32"],
            "accounts.*.used_in_income" => ["required", "boolean"],
            "accounts.*.used_in_expences" => ["required", "boolean"],
            "accounts.*.count_to_summary" => ["required", "boolean"],
            "accounts.*.show_on_charts" => ["required", "boolean"],
            "accounts.*.start_date" => ["required", "date", "after_or_equal:1970-01-01"],
            "accounts.*.start_balance" => ["required", "numeric", "max:1e11", "min:-1e11", "not_in:-1e11,1e11"]
        ])["accounts"];

        $income = request()->validate([
            "income" => ["present", "array"],
            "income.*.date" => ["required", "date", "after_or_equal:1970-01-01", new CorrectDateIncomeExpences($accounts)],
            "income.*.title" => ["required", "string", "max:64"],
            "income.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "income.*.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "income.*.currency" => ["required", "exists:currencies,ISO"],
            "income.*.category_id" => ["required", "integer", new ValidCategoryOrAccount($categories, true)],
            "income.*.account_id" => ["required", "integer", new ValidCategoryOrAccount($accounts, true)]
        ])["income"];

        $expences = request()->validate([
            "expences" => ["present", "array"],
            "expences.*.date" => ["required", "date", "after_or_equal:1970-01-01", new CorrectDateIncomeExpences($accounts)],
            "expences.*.title" => ["required", "string", "max:64"],
            "expences.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:0,1e7"],
            "expences.*.price" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "expences.*.currency" => ["required", "exists:currencies,ISO"],
            "expences.*.category_id" => ["required", "integer", new ValidCategoryOrAccount($categories, true)],
            "expences.*.account_id" => ["required", "integer", new ValidCategoryOrAccount($accounts, true)]
        ])["expences"];

        $transfers = request()->validate([
            "transfers" => ["present", "array"],
            "transfers.*.date" => ["required", "date", "after_or_equal:1970-01-01", new CorrectTransferDate($accounts)],
            "transfers.*.source_value" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "transfers.*.source_account_id" => ["required", "integer", "different:transfers.*.target_account_id", new ValidTransferAccount($accounts)],
            "transfers.*.target_value" => ["required", "numeric", "max:1e11", "min:0", "not_in:0,1e11"],
            "transfers.*.target_account_id" => ["required", "integer", "different:transfers.*.source_account_id", new ValidTransferAccount($accounts)],
        ])["transfers"];

        // Delete all data from the account
        auth()->user()->categories()->delete();
        auth()->user()->accounts()->delete();
        auth()->user()->income()->delete();
        auth()->user()->expences()->delete();
        auth()->user()->transfers()->delete();

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

        // $accountIDs is an array which maps the index in the $accounts array to the id of a account.
        $accountIDs = [ 0 => null ];

        // Restore accounts
        foreach ($accounts as $account) {
            array_push($accountIDs,
                auth()->user()->accounts()->create([
                    ...$account,
                    "currency_id" => $currencies[$account["currency"]]
                ])->id
            );
        }

        // Restore income
        foreach ($income as $item) {
            auth()->user()->income()->create([
                ...$item,
                "currency_id" => $currencies[$item["currency"]],
                "category_id" => $categoryIDs[$item["category_id"]],
                "account_id" => $accountIDs[$item["account_id"]]
            ]);
        }

        // Restore expences
        foreach ($expences as $item) {
            auth()->user()->expences()->create([
                ...$item,
                "currency_id" => $currencies[$item["currency"]],
                "category_id" => $categoryIDs[$item["category_id"]],
                "account_id" => $accountIDs[$item["account_id"]]
            ]);
        }

        // Restore transfers
        foreach ($transfers as $item) {
            auth()->user()->transfers()->create([
                ...$item,
                "source_account_id" => $accountIDs[$item["source_account_id"]],
                "target_account_id" => $accountIDs[$item["target_account_id"]]
            ]);
        }

        // Restore extensions
        if (request()->has("extensions")) {
            request()->validate(["extensions" => ["required", "array"]]);

            // Restore cash handling
            if (request()->has("extensions.cashan") && auth()->user()->extensionCodes->contains("cashan")) {
                $extensionData = request()->validate([
                    "extensions.cashan" => ["required", "array"],

                    "extensions.cashan.cash" => ["present", "array"],
                    "extensions.cashan.cash.*.currency" => ["required", "exists:currencies,ISO"],
                    "extensions.cashan.cash.*.value" => ["required", "numeric", "min:0", "max:1e8", "not_in:0,1e8"],
                    "extensions.cashan.cash.*.amount" => ["required", "integer", "min:1", "max:1e7"],

                    "extensions.cashan.accounts" => ["present", "array"],
                    "extensions.cashan.accounts.*.currency" => ["required", "exists:currencies,ISO", "distinct"],
                    "extensions.cashan.accounts.*.account_id" => ["required", "integer", new ValidCategoryOrAccount($accounts)]
                ])["extensions"]["cashan"];

                // Delete account's cash data
                auth()->user()->cash()->detach();
                auth()->user()->cashAccounts()->detach();

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

                // Restore cash accounts
                foreach ($extensionData["accounts"] as $item) {
                    auth()->user()->cashAccounts()->attach($accountIDs[$item["account_id"]]);
                }
            }

            // Restore report management
            if (request()->has("extensions.report") && auth()->user()->extensionCodes->contains("report")) {
                $extensionData = request()->validate([
                    "extensions.report.reports" => ["present", "array"],
                    "extensions.report.reports.*.title" => ["required", "string", "max:64"],
                    "extensions.report.reports.*.income_addition" => ["required", "boolean"],
                    "extensions.report.reports.*.sort_dates_desc" => ["required", "boolean"],
                    "extensions.report.reports.*.calculate_sum" => ["required", "boolean"],
                    "extensions.report.reports.*.show_columns" => ["required", "integer", "min:0", "max:127"],

                    "extensions.report.reports.*.queries" => ["present", "array"],
                    "extensions.report.reports.*.queries.*.query_data" => ["required", "string", "in:income,expences"],
                    "extensions.report.reports.*.queries.*.min_date" => ["present", "nullable", "date", new DateBeforeOrEqualField("max_date")],
                    "extensions.report.reports.*.queries.*.max_date" => ["present", "nullable", "date"],
                    "extensions.report.reports.*.queries.*.title" => ["present", "nullable", "string", "max:64"],
                    "extensions.report.reports.*.queries.*.min_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:1e7", new ValueLessOrEqualField("max_amount")],
                    "extensions.report.reports.*.queries.*.max_amount" => ["present", "nullable", "numeric", "max:1e7", "min:0", "not_in:1e7"],
                    "extensions.report.reports.*.queries.*.min_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:1e11", new ValueLessOrEqualField("max_price")],
                    "extensions.report.reports.*.queries.*.max_price" => ["present", "nullable", "numeric", "max:1e11", "min:0", "not_in:1e11"],
                    "extensions.report.reports.*.queries.*.currency" => ["present", "nullable", "exists:currencies,ISO"],
                    "extensions.report.reports.*.queries.*.category_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount($categories, "query_data")],
                    "extensions.report.reports.*.queries.*.account_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount($accounts)],

                    "extensions.report.reports.*.additionalEntries" => ["present", "array"],
                    "extensions.report.reports.*.additionalEntries.*.date" => ["required", "date", "after_or_equal:1970-01-01"],
                    "extensions.report.reports.*.additionalEntries.*.title" => ["required", "string", "max:64"],
                    "extensions.report.reports.*.additionalEntries.*.amount" => ["required", "numeric", "max:1e7", "min:0", "not_in:1e7"],
                    "extensions.report.reports.*.additionalEntries.*.price" => ["required", "numeric",  "max:1e11", "min:-1e11", "not_in:1e11,-1e11"],
                    "extensions.report.reports.*.additionalEntries.*.currency" => ["required", "exists:currencies,ISO"],
                    "extensions.report.reports.*.additionalEntries.*.category_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount($categories)],
                    "extensions.report.reports.*.additionalEntries.*.account_id" => ["present", "nullable", "integer", new ValidCategoryOrAccount($accounts)],

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
                            "account_id" => $query["account_id"] ? $accountIDs[$query["account_id"]] : null
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
                            "account_id" => $entry["account_id"] ? $accountIDs[$entry["account_id"]] : null
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
