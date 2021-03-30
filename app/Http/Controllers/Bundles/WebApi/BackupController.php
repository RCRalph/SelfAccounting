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
                "last_backup" => Carbon::now()->subDays(1),
                "last_restoration" => Carbon::now()->subDays(1)
            ]);

            return response()->json([
                "canCreate" => true,
                "canRestore" => true
            ]);
        }
        $lastBackup = Carbon::parse(auth()->user()->backup->last_backup);
        $lastRestoration = Carbon::parse(auth()->user()->backup->last_restoration);

        return response()->json([
            "canCreate" => Carbon::now()->subDays(1)->gte($lastBackup),
            "canRestore" => Carbon::now()->subDays(1)->gte($lastRestoration)
        ]);
    }

    public function createBackup()
    {

    }
}
