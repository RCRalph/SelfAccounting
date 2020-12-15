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

        $user["profile_picture"] = $this->getProfilePictureLink($user["profile_picture"]);
        $user["premium"] = $this->checkPremium(auth()->user());

        return response()->json(compact("user"));
    }
}
