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
                "tooltip" => (!$backup->last_backup && now()->subDays(1)->lt($backup->last_backup)) ?
                    "You can only create a backup once every 24 hours." : false
            ],
            "restore" => [
                "last" => $backup->last_restoration,
                "tooltip" => (!$backup->last_restoration && now()->subDays(1)->gte($backup->last_restoration)) ?
                    "You can only restore a backup once every 24 hours." : false
            ]
        ];

        if (now()->subDays(7)->lte(auth()->user()->created_at)) {
            $data["restore"]["tooltip"] = "You can only restore a backup 7 days after creating an account.";
        }

        return response()->json(compact("data"));
    }
}
