<?php

namespace App\Http\Controllers\Admin\Web_Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Extension;
use App\ExtensionImage;

class ExtensionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "admin"]);
    }

    public function extensions() // Get list of extensions
    {
        $users = Extension::select(["id", "title"])
            ->orderBy("id")
            ->paginate(20);

        return response()->json(compact("users"));
    }

    private function getExtensionDetails($toExclude = 0)
    {
        $extensions = Extension::where("id", "<>", $toExclude)
            ->select("title", "code")
            ->get()
            ->toArray();

        $retArr = [
            "titles" => [],
            "codes" => []
        ];

        foreach ($extensions as $key => $value) {
            array_push($retArr["titles"], $value["title"]);
            array_push($retArr["codes"], $value["code"]);
        }

        return $retArr;
    }

    public function getCreateData()
    {
        $details = $this->getExtensionDetails();

        $titles = $details["titles"];
        $codes = $details["codes"];

        return response()->json(compact("titles", "codes"));
    }

    public function details(Extension $extension)
    {
        $details = $this->getExtensionDetails($extension->id);
        $titles = $details["titles"];
        $codes = $details["codes"];

        $gallery = $extension->gallery
            ->map(function ($item) {
                $item->image = $this->getLocalImageLink(
                    $this->PUBLIC_DIRECTORIES[1],
                    $item->image
                );

                return collect($item)->only("id", "image");
            });

        $extension = collect($extension)->forget(["created_at", "updated_at", "thumbnail", "gallery"]);

        return response()->json(compact("titles", "extension", "gallery", "codes"));
    }

    public function updateGallery(Extension $extension)
    {
        $data = request()->validate([
            "gallery.*" => ["required", "integer", "exists:extension_images,id"]
        ]);

        // Get IDs of images that belong to this extension
        $ids = $extension->gallery->map(function ($item) {
            return $item->id;
        })->toArray();

        // Get IDs of images to delete
        $toRemove = array_values(array_filter(
            $ids,
            fn ($item) => !in_array($item, $data["gallery"])
        ));

        // Delete images from DB and storage
        $images = ExtensionImage::whereIn("id", $toRemove)->get();
        ExtensionImage::whereIn("id", $toRemove)->delete();

        foreach ($images as $image) {
            $this->deleteImage($this->PUBLIC_DIRECTORIES[1], $image->image, "public");
        }

        // Get gallery as return object
        $gallery = $extension->fresh()->gallery->map(function ($item) {
            $item->image = $this->getLocalImageLink(
                $this->PUBLIC_DIRECTORIES[1],
                $item->image
            );
            return collect($item)->only("id", "image");
        });

        return response()->json(compact("gallery"));
    }
}
