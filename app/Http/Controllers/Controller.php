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

    public $BUNDLE_INFO_LIST = [
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
                $retArr = auth()->user()->only("darkmode", "profile_picture", "id", "hide_all_tutorials");
                $retArr["profile_picture"] = $this->getProfilePictureLink(auth()->user()->profile_picture);
                $retArr["bundle_info"] = $this->BUNDLE_INFO_LIST;

                // Select bundles available to the user
                $retArr["bundles"] = auth()->user()->premiumBundles
                    ->merge(auth()->user()->bundles)
                    ->filter(
                        fn ($item) => $item->pivot->enabled === null ? true : $item->pivot->enabled
                    );

                // Update user's last page visit
                auth()->user()->update([
                    "last_page_visit" => Carbon::now()
                ]);

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

    public function deleteImage($directory, $name, $disk = "s3")
    {
        Storage::disk($disk)->delete("$directory/$name");
    }

    public function uploadImage($image, $directory, $delete = false, $fit = [], $disk = "s3")
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
        $endpoint = config("filesystems.disks.s3.endpoint");
        $bucket = config("filesystems.disks.s3.bucket");

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

    public function addNoBreakSpaces($text)
    {
        $text = explode(" ", $text);

        for ($i = count($text) - 2; $i >= 0; $i--) {
            if (strlen($text[$i]) == 1 && !in_array($text[$i], ["#", "-"])) {
                $text[$i + 1] = $text[$i] . "&nbsp;" . $text[$i + 1];
                array_splice($text, $i, 1);
                $i++;
            }
        }

        return implode(" ", $text);
    }

    public function getTutorial($directory)
    {
        return $this->addNoBreakSpaces(
            Storage::disk("local")->get("files/tutorials/$directory")
        );
    }

    public function getLastUsedCurrencies()
    {
        $id = auth()->user()->id;

        return Cache::remember(
            "last-used-currencies-$id",
            now()->addMinutes(15),
            function () {
                $data = auth()->user()->income
                    ->concat(auth()->user()->outcome)
                    ->sortBy("updated_at")
                    ->groupBy("currency_id");

                $retArr = [];
                foreach ($data as $currency => $entries) {
                    $retArr[$currency] = collect($entries)->last()["updated_at"];
                }

                return collect($retArr)->sortDesc()->keys()->toArray();
            }
        );
    }

    public function getBalance($income, $outcome, $means, $categories, $meansToShow, $categoriesToShow)
    {
        $incomeByMeans = $income
            ->whereIn("mean_id", $meansToShow)
            ->groupBy("mean_id")
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

        $outcomeByMeans = $outcome
            ->whereIn("mean_id", $meansToShow)
            ->groupBy("mean_id")
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

        $balanceByMeans = $incomeByMeans;
        foreach ($outcomeByMeans as $mean => $balance) {
            if (array_key_exists($mean, $balanceByMeans)) {
                $balanceByMeans[$mean] -= $balance;
            }
            else {
                $balanceByMeans[$mean] = -$balance;
            }
        }

        $balanceByCategories = $outcome
            ->whereIn("category_id", $categoriesToShow)
            ->groupBy("category_id");

        foreach ($balanceByCategories->keys() as $categoryID) {
            $category = $categories
                ->where("id", $categoryID)
                ->first();

            foreach ($balanceByCategories[$categoryID] as $key => $value) {
                $limitedByStart = (
                    $category->start_date != null &&
                    strtotime($value["date"]) < strtotime($category->start_date)
                );

                $limitedByEnd = (
                    $category->end_date != null &&
                    strtotime($value["date"]) > strtotime($category->end_date)
                );

                if ($limitedByStart || $limitedByEnd) {
                    unset($balanceByCategories[$categoryID][$key]);
                }
            }
        }

        $balanceByCategories = $balanceByCategories
            ->map(fn ($item) => $item->sum("value"))
            ->toArray();

        $currentBalance = [];
        $foundMeanIDs = [];

        foreach ($balanceByMeans as $meanID => $balance) {
            $foundMean = $means->where("id", $meanID)->first();

            array_push($currentBalance, [
                "mean_id" => $foundMean->id,
                "name" => $foundMean->name,
                "balance" => $balance + $foundMean->first_entry_amount
            ]);

            array_push($foundMeanIDs, $foundMean->id);
        }

        // Add means without any entries
        foreach ($means->whereNotIn("id", $foundMeanIDs) as $mean) {
            array_push($currentBalance, [
                "mean_id" => $mean->id,
                "name" => $mean->name,
                "balance" => $mean->first_entry_amount * 1 // Convert string to number
            ]);
        }

        // Add categories marked as count to summary
        foreach ($balanceByCategories as $categoryID => $balance) {
            $foundCategory = $categories->where("id", $categoryID)->first();

            array_push($currentBalance, [
                "category_id" => $foundCategory->id,
                "name" => $foundCategory->name,
                "balance" => $balance
            ]);
        }

        // Sort by balance DESC
        usort($currentBalance, function ($a, $b) {
            return $b["balance"] - $a["balance"];
        });

        // Round to 2 decimal places
        foreach ($currentBalance as $i => $val) {
            $currentBalance[$i]["balance"] = round($val["balance"], 2);
        }

        return $currentBalance;
    }

    public function addNamesToPaginatedIOItems($items, Currency $currency)
    {
        $paginatedData = $items->getCollection()->toArray();

        $categories = auth()->user()->categories()
            ->where("currency_id", $currency->id)
            ->select("id", "name")
            ->get()
            ->keyBy("id");

        $means = auth()->user()->meansOfPayment()
            ->where("currency_id", $currency->id)
            ->select("id", "name")
            ->get()
            ->keyBy("id");

        foreach ($paginatedData as $i => $item) {
            $paginatedData[$i]["amount"] *= 1;
            $paginatedData[$i]["price"] *= 1;
            $paginatedData[$i]["value"] = round($item["amount"] * $item["price"] * ($item["type"] ?? 1), 2);
            $paginatedData[$i]["category"] = $categories[$item["category_id"]]->name ?? "N/A";
            $paginatedData[$i]["mean"] = $means[$item["mean_id"]]->name ?? "N/A";

            unset($paginatedData[$i]["type"], $paginatedData[$i]["category_id"], $paginatedData[$i]["mean_id"]);
        }

        $items->setCollection(collect($paginatedData));

        return $items;
    }
}
