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
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $directories = [
        "bundles/thumbnails",
        "bundles/gallery",
        "profile_pictures"
    ];

    public function getProfilePictureLink($profile_picture)
    {
        if (preg_match("/Emoji[1-6].png/", $profile_picture) || $profile_picture == "EmojiAdmin.png") {
            return "/img/avatars/$profile_picture";
        }

        return $this->getImageLink(
            $this->directories[2],
            $profile_picture
        );
    }

    public function getDataForPageRender()
    {
        $auth = auth()->user();
        $retArr = $auth->only("darkmode", "profile_picture");
        $retArr["profile_picture"] = $this->getProfilePictureLink($auth->profile_picture);

        $retArr["bundle_info"] = [
            "charts" => [
                "icon" => "fas fa-chart-bar",
                "directory" => "charts"
            ]
        ];

        // Do the same but with collections
		$retArr["bundles"] = $auth->premium_bundles->merge($auth->bundles)->filter(
            fn ($item) => $item->pivot->enabled === null ? true : $item->pivot->enabled
        );

        return $retArr;
    }

    public function checkPremium($user)
    {
        return $user->admin ||
            $user->premium_expiration == null ||
            Carbon::parse($user->premium_expiration)
                ->addDay(1)
                ->timestamp >= Carbon::now()->timestamp;
    }

    public function deleteImage($directory, $name)
    {
        Storage::disk("ibm-cos")->delete("$directory/$name");
    }

    public function uploadImage($image, $directory, $delete = false, $fit = [])
    {
        $img = Image::make($image);
        if (count($fit) == 2) {
            $img->fit($fit[0], $fit[1]);
        }

        do {
            $filename = Str::random(50) . "." . $image->extension();
        } while (Storage::disk("ibm-cos")->has("$directory/$filename"));

        if ($delete) {
            $this->deleteImage($directory, $delete);
        }
        Storage::disk("ibm-cos")->put("$directory/$filename", $img->stream());

        return $filename;
    }

    public function getImageLink($directory, $filename)
    {
        $endpoint = env('IBM_COS_ENDPOINT');
        $bucket = env('IBM_COS_BUCKET');

        return "$endpoint/$bucket/$directory/$filename";
    }

    public function getTextColorOnBackgroundRGB($rgb)
    {
        return $rgb[0] * 0.299 + $rgb[1] * 0.587 + $rgb[2] * 0.114 > 150 ? "black" : "white";
    }

    public function getLastCurrency()
    {
        $userId = auth()->user()->id;

        return Cache::remember(
            "last-currency-$userId",
            now()->addMinutes(15),
            function() {
                $income = auth()->user()->income;
                $outcome = auth()->user()->outcome;

                $last = $income->merge($outcome)
                    ->sortBy("updated_at")->last();

                return $last != null ? $last->currency_id : 1;
            }
        );
    }
}
