<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDataForPageRender()
    {
        $user = auth()->user()->only("darkmode", "profile_picture");

        if (preg_match("/Emoji[1-6].png/", $user["profile_picture"])) {
            $user["profile_picture"] = "/img/avatars/" . $user["profile_picture"];
        }
        else {
            $user["profile_picture"] = env("IBM_COS_ENDPOINT") . "/" . env("IBM_COS_BUCKET") . "/profile_pictures/" . $user["profile_picture"];
        }

        return $user;
    }

    public function checkPremium($user)
    {
        return $user->admin ||
            $user->premium_expiration == null ||
            Carbon::parse($user->premium_expiration)->addDay(1)->timestamp >= Carbon::now()->timestamp;
    }
}
