<?php

namespace App\Http\Controllers\Admin\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Admin;

use App\User;
use App\Bundle;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "admin"]);
    }

    public function users() // Get list of users
    {
        $users = User::select(["id", "email"])
            ->orderBy("id")
            ->paginate(20);

        return response()->json(compact("users"));
    }

    public function details(User $user) // Get user's details
    {
        $bundles = Bundle::all()->map(function ($item) {
            return $item->only("id", "title");
        });

        $user->profile_picture = $this->getProfilePictureLink($user->profile_picture);

        // Get only ID and title from user's bundles (other properties are unnecessary)
        foreach ($user->bundles as $key => $bundle) {
            $user->bundles[$key] = $bundle->only("id", "title");
        }

        $hasBackupBundle = $this->hasBundle("backup", $user->id);

        return response()->json(compact("user", "bundles", "hasBackupBundle"));
    }

    public function update(User $user) // Update user's details
    {
        $data = request()->validate([
            "bundleIDs.*" => ["required", "integer", "exists:bundles,id"]
		]);

		$bundlesToSet = array_key_exists("bundleIDs", $data) ? $data["bundleIDs"] : []; // If there aren't any bundles, the key in $data won't exist and this will throw an error
        $userBundleIDs = $user->bundles // Get only IDs, other properties are unnecesarry
            ->map(fn ($item) => $item->id)
			->toArray();

		// Get bundles to toggle by removing common elements from these two arrays
		$bundlesToToggle = [];
		foreach ($bundlesToSet as $key => $bundleID) {
			if (!in_array($bundleID, $userBundleIDs)) {
				array_push($bundlesToToggle, $bundleID);
			}
		}

		foreach ($userBundleIDs as $key => $bundleID) {
			if (!in_array($bundleID, $bundlesToSet)) {
				array_push($bundlesToToggle, $bundleID);
			}
		}

		$user->bundles()->toggle(Bundle::whereIn("id", $bundlesToToggle)->get());

        $bundlesToReturn = $user->fresh()->bundles->map(function ($item) {
            return ["id" => $item->id, "title" => $item->title];
        });

		return response()->json([
			"bundles" => $bundlesToReturn
		]);
    }

    public function enableBackup(User $user)
    {
        if ($this->hasBundle("backup", $user->id)) {
            $user->backup->update([
                "last_backup" => now()->subDays(1)
            ]);

            return response("", 200);
        }

        return response("User doesn't have the backup bundle", 422);
    }

    public function enableRestoration(User $user)
    {
        if ($this->hasBundle("backup", $user->id)) {
            $user->backup->update([
                "last_restoration" => now()->subDays(1)
            ]);

            return response("", 200);
        }

        return response("User doesn't have the backup bundle", 422);
    }
}
