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

use App\Bundle;
use App\Currency;
use App\Cash;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $COS_DIRECTORIES = [
        "profile_pictures"
    ];

    public $PUBLIC_DIRECTORIES = [
        "bundles/thumbnails",
        "bundles/gallery"
    ];

    public $TABLE_HEAD = ["date", "title", "amount", "price", "value", "category", "mean"];

    public function getProfilePictureLink($profile_picture)
    {
        if (preg_match("/Emoji([1-6]|Admin).png/", $profile_picture)) {
            return "/storage/avatars/$profile_picture";
        }

        return $this->getCosLink(
            $this->COS_DIRECTORIES[0],
            $profile_picture
        );
    }

    public function getDataForPageRender()
    {
        $id = auth()->user()->id;
        return Cache::remember(
            "page-render-data-$id",
            now()->addMinutes(15),
            function () {
                $retArr = auth()->user()->only("darkmode", "profile_picture");
                $retArr["profile_picture"] = $this->getProfilePictureLink(auth()->user()->profile_picture);

                $retArr["bundle_info"] = [
                    "charts" => [
                        "icon" => "fas fa-chart-bar",
                        "directory" => "charts"
                    ],
                    "backup" => [
                        "icon" => "fas fa-hdd",
                        "directory" => "backup"
                    ],
                    "cashan" => [
                        "icon" => "fas fa-money-bill-wave",
                        "directory" => "cash"
                    ],
                    "report" => [
                        "icon" => "fas fa-newspaper",
                        "directory" => "reports"
                    ]
                ];

                // Do the same but with collections
                $retArr["bundles"] = auth()->user()->premiumBundles
                    ->merge(auth()->user()->bundles)
                    ->filter(
                        fn ($item) => $item->pivot->enabled === null ? true : $item->pivot->enabled
                    );

                return $retArr;
            }
        );
    }

    public function checkPremium($user)
    {
        return $user->admin ||
            $user->premium_expiration == null ||
            Carbon::parse($user->premium_expiration)
                ->addDay(1)
                ->timestamp >= Carbon::now()->timestamp;
    }

    public function deleteImage($directory, $name, $disk = "ibm-cos")
    {
        Storage::disk($disk)->delete("$directory/$name");
    }

    public function uploadImage($image, $directory, $delete = false, $fit = [], $disk = "ibm-cos")
    {
        $img = Image::make($image);
        if (count($fit) == 2) {
            $img->fit($fit[0], $fit[1]);
        }

        do {
            $filename = Str::random(50) . "." . $image->extension();
        } while (Storage::disk($disk)->has("$directory/$filename"));

        if ($delete) {
            $this->deleteImage($directory, $delete, $disk);
        }
        Storage::disk($disk)->put("$directory/$filename", $img->stream());

        return $filename;
    }

    public function getCosLink($directory, $filename)
    {
        $endpoint = config("object-storage.endpoint");
        $bucket = config("object-storage.bucket");

        return "$endpoint/$bucket/$directory/$filename";
    }

    public function getLocalImageLink($directory, $filename)
    {
        return "/storage/$directory/$filename";
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
            function () {
                $income = auth()->user()->income;
                $outcome = auth()->user()->outcome;

                $last = $income->merge($outcome)
                    ->sortBy("updated_at")->last();

                return $last != null ? $last->currency_id : 1;
            }
        );
    }

    public function hasBundle($code, $id = null)
    {
        $user = $id == null ? auth()->user() : User::find($id);

        $bundle = Bundle::firstWhere("code", $code);
        return $user->bundles->contains($bundle) ||
            $user->premiumBundles->contains($bundle);
    }

    public function getCurrencies()
    {
        return Cache::remember(
            "currencies",
            now()->addHours(1),
            fn () => Currency::all()
        );
    }

    public function getCash()
    {
        return Cache::remember(
            "cash",
            now()->addHours(1),
            fn () => Cash::all()
                ->sortByDesc("value")
                ->groupBy("currency_id")
        );
    }

    public function getCashMeans()
    {
        $cashMeansList = auth()->user()->cashMeans
            ->map(fn ($item) => $item->only("currency_id", "id"))
            ->groupBy("currency_id");

        $cashMeans = [];
        foreach ($cashMeansList as $currency => $value) {
            $cashMeans[$currency] = $value[0]["id"];
        }

        foreach ($this->getCurrencies() as $currency) {
            if (!isset($cashMeans[$currency->id])) {
                $cashMeans[$currency->id] = null;
            }
        }

        return $cashMeans;
    }
}
