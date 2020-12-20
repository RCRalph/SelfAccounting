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
        $this->middleware(["auth", Admin::class]);
    }

    public function users() // Get list of users
    {
        $users = User::where("id", ">", "0")
            ->select("id", "email")
            ->orderBy("id")
            ->paginate(20);

        return response()->json(compact("users"));
    }

    public function details() // Get user's details
    {
        $id = request()->validate([
            "id" => ["required", "integer", "exists:users"]
        ])["id"];

        $bundles = Bundle::all()->map(function($item) {
            return $item->only("id", "title");
        });

        $user = User::find($id);
        $user["profile_picture"] = $this->getProfilePictureLink($user["profile_picture"]);

        // Get only ID and title from user's bundles (other properties are unnecessary)
        foreach ($user["bundles"] as $key => $bundle) {
            $user["bundles"][$key] = $bundle->only("id", "title");
        }

        return response()->json(compact("user", "bundles"));
    }

    public function update() // Update user's details
    {
        $data = request()->validate([
            "id" => ["required", "integer", "exists:users"],
            "bundleIDs.*" => ["required", "integer", "exists:bundles,id"]
		]);

		$user = User::find($data["id"]);
		$bundlesToSet = array_key_exists("bundleIDs", $data) ? $data["bundleIDs"] : []; // If there aren't any bundles, the key in $data won't exist and this will throw an error
		$userBundleIDs = $user->bundles->map(function($item) { // Get only IDs, other properties are unnecesarry
			return $item->id;
		})->toArray();

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

		foreach ($bundlesToToggle as $bundleID) {
			$user->bundles()->toggle(Bundle::find($bundleID));
        }

        $bundlesToReturn = $user->fresh()->bundles->map(function($item) {
            return ["id" => $item->id, "title" => $item->title];
        });

		return response()->json([
			"bundles" => $bundlesToReturn
		]);
    }
}
