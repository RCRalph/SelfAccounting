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
        $this->middleware(["auth", Admin::class]);
    }

    public function bundles() // Get list of bundles
    {
        $users = Bundle::where("id", ">", "0")
            ->select("id", "title")
            ->orderBy("id")
            ->paginate(20);

        return response()->json(compact("users"));
    }

    private function getBundleTitles($toExclude = "")
    {
        $titles = Bundle::all()->map(function($item) {
            return $item->only("title");
        })->toArray();

        foreach ($titles as $key => $value) {
            $titles[$key] = strtolower($value["title"]);
        }

        $titles = array_values(array_filter(
            $titles,
            fn ($item) => $item != strtolower($toExclude)
        ));

        return $titles;
    }

    public function getCreateData()
    {
        $titles = $this->getBundleTitles();

        return response()->json(compact("titles"));
    }

    public function details(Bundle $bundle)
    {
        $titles = $this->getBundleTitles($bundle->title);
        $gallery = $bundle->gallery->map(function($item) {
            $item->image = $this->getImageLink(
                $this->directories["bundles-gallery"],
                $item->image
            );
            return collect($item)->only("id", "image");
        });
        $bundle = collect($bundle)->forget(["created_at", "updated_at", "thumbnail", "gallery"]);

        return response()->json(compact("titles", "bundle", "gallery"));
    }

    public function updateGallery(Bundle $bundle)
    {
        $data = request()->validate([
            "gallery.*" => ["required", "integer", "exists:bundle_images,id"]
        ]);

        // Get IDs of images that belong to this bundle
        $ids = $bundle->gallery->map(function($item) {
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
            Storage::disk("ibm-cos")->delete("bundles/gallery/$image->image");
        }

        // Get gallery as return object
        $gallery = $bundle->fresh()->gallery->map(function($item) {
            $item->image = $this->getImageLink(
                $this->directories["bundles-gallery"],
                $item->image
            );
            return collect($item)->only("id", "image");
        });

        return response()->json(compact("gallery"));
    }
}
