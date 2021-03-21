<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Admin;
use Illuminate\Validation\Rule;

use App\User;
use App\Bundle;
use App\BundleImage;

class BundlesController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "admin"]);
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
            "code" => ["required", "string", "min:6", "max:6", "unique:bundles,code"],
            "price" => ["required", "numeric", "min:0", "max:1000", "not_in:1000"],
            "thumbnail" => ["required", "image"],
            "short_description" => ["required", "string"],
            "description" => ["required", "string"]
        ]);

        $data["code"] = strtolower($data["code"]);
        $data["thumbnail"] = $this->uploadImage(
            $data["thumbnail"],
            $this->PUBLIC_DIRECTORIES[0],
            false,
            [],
            "public"
        );
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
            "code" => ["required", "string", "min:6", "max:6", Rule::unique("bundles", "code")->ignore($bundle->id)],
            "price" => ["required", "numeric", "min:0", "max:1000", "not_in:1000"],
            "thumbnail" => ["nullable", "image"],
            "short_description" => ["required", "string"],
            "description" => ["required", "string"]
        ]);

        $data["code"] = strtolower($data["code"]);

        if (array_key_exists("thumbnail", $data)) {
            $data["thumbnail"] = $this->uploadImage(
                $data["thumbnail"],
                $this->PUBLIC_DIRECTORIES[0],
                $bundle->thumbnail,
                [],
                "public"
            );
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

        $image = $this->uploadImage(
            $image,
            $this->PUBLIC_DIRECTORIES[1],
            false,
            [],
            "public"
        );

        BundleImage::create([
            "bundle_id" => $bundle->id,
            "image" => $image
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
        foreach ($bundle->gallery as $bundleImage) {
            $this->deleteImage($this->PUBLIC_DIRECTORIES[1], $bundleImage->image);
        }

        $this->deleteImage($this->PUBLIC_DIRECTORIES[0], $bundle->thumbnail);
        $bundle->delete();

        return redirect("/admin/bundles");
    }
}
