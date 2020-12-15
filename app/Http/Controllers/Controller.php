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

    public function getProfilePictureLink($profile_picture) {
        if (preg_match("/Emoji[1-6].png/", $profile_picture) || $profile_picture == "EmojiAdmin.png") {
            $profile_picture = "/img/avatars/$profile_picture";
        }
        else {
            $endpoint = env('IBM_COS_ENDPOINT');
            $bucket = env('IBM_COS_BUCKET');
            $profile_picture = "$endpoint/$bucket/profile_pictures/$profile_picture";
        }

        return $profile_picture;
    }

    public function getDataForPageRender()
    {
        $user = auth()->user()->only("darkmode", "profile_picture");
        $user["profile_picture"] = $this->getProfilePictureLink($user["profile_picture"]);

        return $user;
    }

    public function checkPremium($user)
    {
        return $user->admin ||
            $user->premium_expiration == null ||
            Carbon::parse($user->premium_expiration)->addDay(1)->timestamp >= Carbon::now()->timestamp;
    }
}
