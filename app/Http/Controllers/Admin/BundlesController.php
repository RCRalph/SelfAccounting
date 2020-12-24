<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\User;
use App\Bundle;
use App\BundleImage;

class BundlesController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", Admin::class]);
    }

    public function list()
    {
        $pageData = $this->getDataForPageRender();

        return view("admin.bundles.list", compact("pageData"));
    }

    public function create()
    {
        $pageData = $this->getDataForPageRender();

        return view("admin.bundles.create", compact("pageData"));
    }

    public function store()
    {
        $data = request()->validate([
            "title" => ["required", "string", "max:64", "unique:bundles,title"],
            "price" => ["required", "numeric", "gte:0", "lt:1000"],
            "thumbnail" => ["required", "image"],
            "short_description" => ["required", "string"],
            "description" => ["required", "string"]
        ]);

        $img = Image::make($data["thumbnail"]);
        do {
            $fileName = Str::random(50) . "." . $data["thumbnail"]->extension();
        } while (Storage::disk("ibm-cos")->has("bundles/thumbnails/" . $fileName));

        Storage::disk("ibm-cos")->put("bundles/thumbnails/" . $fileName, $img->stream());
        $data["thumbnail"] = $fileName;

        $bundle = Bundle::create($data);

        return redirect("/admin/bundles/$bundle->id");
    }

    public function edit(Bundle $bundle) {
        $pageData = $this->getDataForPageRender();

        return view("admin.bundles.edit", compact("pageData", "bundle"));
    }

    public function update(Bundle $bundle) {
        $data = request()->validate([
            "title" => ["required", "string", "max:64", Rule::unique("bundles", "title")->ignore($bundle->id)],
            "price" => ["required", "numeric", "gte:0", "lt:1000"],
            "thumbnail" => ["nullable", "image"],
            "short_description" => ["required", "string"],
            "description" => ["required", "string"]
        ]);

        if (array_key_exists("thumbnail", $data)) {
            $img = Image::make($data["thumbnail"]);
            do {
                $fileName = Str::random(50) . "." . $data["thumbnail"]->extension();
            } while (Storage::disk("ibm-cos")->has("bundles/thumbnails/" . $fileName));

			Storage::disk("ibm-cos")->delete("bundles/thumbnails/" . $bundle->thumbnail);
            Storage::disk("ibm-cos")->put("bundles/thumbnails/" . $fileName, $img->stream());

            $data["thumbnail"] = $fileName;
        }

        $bundle->update($data);

        return redirect("/admin/bundles/$bundle->id");
    }

    public function addImage(Bundle $bundle)
    {
        $pageData = $this->getDataForPageRender();

        return view("admin.bundles.add-image", compact("pageData", "bundle"));
    }

    public function storeImage(Bundle $bundle)
    {
        $image = request()->validate([
            "image" => ["required", "image"]
        ])["image"];
        $img = Image::make($image);

        do {
            $fileName = Str::random(50) . "." . $image->extension();
        } while (Storage::disk("ibm-cos")->has("bundles/gallery/$fileName"));

        Storage::disk("ibm-cos")->put("bundles/gallery/$fileName", $img->stream());
        BundleImage::create([
            "bundle_id" => $bundle->id,
            "image" => $fileName
        ]);

        return redirect("/admin/bundles/$bundle->id");
    }

    public function confirmDeletion(Bundle $bundle)
    {
        $pageData = $this->getDataForPageRender();
        $heading = "Delete bundle";
        $links = [
            "yes" => "/admin/bundles/$bundle->id/delete/confirmed",
            "no" => "/admin/bundles/$bundle->id"
        ];

        return view("shared.confirm-delete", compact("pageData", "heading", "links"));
    }

    public function delete(Bundle $bundle)
    {
        $bundle->delete();
        return redirect("/admin/bundles");
    }
}
