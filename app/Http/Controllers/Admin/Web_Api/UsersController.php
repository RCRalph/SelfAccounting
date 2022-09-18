<?php

namespace App\Http\Controllers\Admin\Web_Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Admin;

use App\Models\User;
use App\Models\Extension;

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
        $extensions = Extension::all()->map(function ($item) {
            return $item->only("id", "title");
        });

        $user->profile_picture = $this->getProfilePictureLink($user->profile_picture);

        // Get only ID and title from user's extensions (other properties are unnecessary)
        foreach ($user->extensions as $key => $extension) {
            $user->extensions[$key] = $extension->only("id", "title");
        }

        $hasBackupExtension = $this->hasExtension("backup", $user->id);

        return response()->json(compact("user", "extensions", "hasBackupExtension"));
    }

    public function update(User $user) // Update user's details
    {
        $data = request()->validate([
            "extensionIDs.*" => ["required", "integer", "exists:extensions,id"]
		]);

		$extensionsToSet = array_key_exists("extensionIDs", $data) ? $data["extensionIDs"] : []; // If there aren't any extensions, the key in $data won't exist and this will throw an error
        $userExtensionIDs = $user->extensions // Get only IDs, other properties are unnecesarry
            ->map(fn ($item) => $item->id)
			->toArray();

		// Get extensions to toggle by removing common elements from these two arrays
		$extensionsToToggle = [];
		foreach ($extensionsToSet as $key => $extensionID) {
			if (!in_array($extensionID, $userextensionIDs)) {
				array_push($extensionsToToggle, $extensionID);
			}
		}

		foreach ($userExtensionIDs as $key => $extensionID) {
			if (!in_array($extensionID, $extensionsToSet)) {
				array_push($extensionsToToggle, $extensionID);
			}
		}

		$user->extensions()->toggle(Extension::whereIn("id", $extensionsToToggle)->get());

        $extensionsToReturn = $user->fresh()->extensions->map(function ($item) {
            return ["id" => $item->id, "title" => $item->title];
        });

		return response()->json([
			"extensions" => $extensionsToReturn
		]);
    }

    public function enableBackup(User $user)
    {
        if ($this->hasExtension("backup", $user->id)) {
            $user->backup->update([
                "last_backup" => now()->subDays(1)
            ]);

            return response("", 200);
        }

        return response("User doesn't have the backup extension", 422);
    }

    public function enableRestoration(User $user)
    {
        if ($this->hasExtension("backup", $user->id)) {
            $user->backup->update([
                "last_restoration" => now()->subDays(1)
            ]);

            return response("", 200);
        }

        return response("User doesn't have the backup extension", 422);
    }
}
