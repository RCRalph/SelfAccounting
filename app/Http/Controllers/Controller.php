<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $directories = [
        "bundle-thumbnails" => "bundles/thumbnails",
        "bundle-gallery" => "bundles/gallery",
        "profile-pictures" => "profile_pictures"
    ];

    public function getProfilePictureLink($profile_picture) {
        if (preg_match("/Emoji[1-6].png/", $profile_picture) || $profile_picture == "EmojiAdmin.png") {
            return "/img/avatars/$profile_picture";
        }

        return $this->getImageLink(
            $this->directories["profile-pictures"],
            $profile_picture
        );
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

    public function uploadImage($image, $directory, $delete = false, $fit = false)
    {
        $img = Image::make($image);
        if ($fit !== false) {
            $img->fit($fit[0], $fit[1]);
        }

        do {
            $filename = Str::random(50) . "." . $image->extension();
        } while (Storage::disk("ibm-cos")->has("$directory/$filename"));

        if ($delete) {
            Storage::disk("ibm-cos")->delete("$directory/$delete");
        }
        Storage::disk("ibm-cos")->put("$directory/$filename", $img->stream());

        return $filename;
    }

    public function getImageLink($directory, $filename) {
        $endpoint = env('IBM_COS_ENDPOINT');
        $bucket = env('IBM_COS_BUCKET');

        return "$endpoint/$bucket/$directory/$filename";
    }
}
