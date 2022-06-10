<?php

namespace App\Http\Controllers\Web_Api;

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
        $user = auth()->user()->only("id", "profile_picture", "username", "created_at", "premium_expiration", "admin", "email", "hide_all_tutorials");

        $user["profile_picture"] = $this->getProfilePictureLink($user["profile_picture"]);
        $user["premium"] = $this->checkPremium(auth()->user());

        return response()->json(compact("user"));
    }
}
