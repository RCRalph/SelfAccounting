<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $user = auth()->user()->only("profile_picture", "username", "created_at", "premium_expiration", "admin", "email");

        if (preg_match("/Emoji[1-6].png/", $user["profile_picture"])) {
            $user["profile_picture"] = "/avatars/" . $user["profile_picture"];
        }
        else {
            $user["profile_picture"] = env("IBM_COS_ENDPOINT") . "/" . env("IBM_COS_BUCKET") . "/profile_pictures/" . $user["profile_picture"];
        }

        $user["premium"] = $this->checkPremium(auth()->user());

        return response()->json(compact("user"));
    }
}
