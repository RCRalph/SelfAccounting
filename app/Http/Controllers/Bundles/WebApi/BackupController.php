<?php

namespace App\Http\Controllers\Bundles\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Backup;

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
        $lastBackup = Carbon::parse(auth()->user()->backup->last_backup);
        $lastRestoration = Carbon::parse(auth()->user()->backup->last_restoration);

        return response()->json([
            "canCreate" => now()->subDays(1)->gte($lastBackup),
            "canRestore" => now()->subDays(1)->gte($lastRestoration)
        ]);
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
}
