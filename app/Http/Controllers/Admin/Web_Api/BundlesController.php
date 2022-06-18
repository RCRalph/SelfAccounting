<?php

namespace App\Http\Controllers\Admin\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Bundle;
use App\BundleImage;

class BundlesController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "admin"]);
    }

    public function bundles() // Get list of bundles
    {
        $users = Bundle::select(["id", "title"])
            ->orderBy("id")
            ->paginate(20);

        return response()->json(compact("users"));
    }

    private function getBundleDetails($toExclude = 0)
    {
        $bundles = Bundle::where("id", "<>", $toExclude)
            ->select("title", "code")
            ->get()
            ->toArray();

        $retArr = [
            "titles" => [],
            "codes" => []
        ];

        foreach ($bundles as $key => $value) {
            array_push($retArr["titles"], $value["title"]);
            array_push($retArr["codes"], $value["code"]);
        }

        return $retArr;
    }

    public function getCreateData()
    {
        $details = $this->getBundleDetails();

        $titles = $details["titles"];
        $codes = $details["codes"];

        return response()->json(compact("titles", "codes"));
    }

    public function details(Bundle $bundle)
    {
        $details = $this->getBundleDetails($bundle->id);
        $titles = $details["titles"];
        $codes = $details["codes"];

        $gallery = $bundle->gallery
            ->map(function ($item) {
                $item->image = $this->getLocalImageLink(
                    $this->PUBLIC_DIRECTORIES[1],
                    $item->image
                );

                return collect($item)->only("id", "image");
            });

        $bundle = collect($bundle)->forget(["created_at", "updated_at", "thumbnail", "gallery"]);

        return response()->json(compact("titles", "bundle", "gallery", "codes"));
    }

    public function updateGallery(Bundle $bundle)
    {
        $data = request()->validate([
            "gallery.*" => ["required", "integer", "exists:bundle_images,id"]
        ]);

        // Get IDs of images that belong to this bundle
        $ids = $bundle->gallery->map(function ($item) {
            return $item->id;
        })->toArray();

        // Get IDs of images to delete
        $toRemove = array_values(array_filter(
            $ids,
            fn ($item) => !in_array($item, $data["gallery"])
        ));

        // Delete images from DB and storage
        $images = BundleImage::whereIn("id", $toRemove)->get();
        BundleImage::whereIn("id", $toRemove)->delete();

        foreach ($images as $image) {
            $this->deleteImage($this->PUBLIC_DIRECTORIES[1], $image->image, "public");
        }

        // Get gallery as return object
        $gallery = $bundle->fresh()->gallery->map(function ($item) {
            $item->image = $this->getLocalImageLink(
                $this->PUBLIC_DIRECTORIES[1],
                $item->image
            );
            return collect($item)->only("id", "image");
        });

        return response()->json(compact("gallery"));
    }
}
