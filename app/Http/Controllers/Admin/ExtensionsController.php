<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Admin;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Extension;
use App\Models\ExtensionImage;

class ExtensionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "admin"]);
    }

    public function list()
    {
        $pageData = $this->getDataForPageRender();

        return view("admin.extensions.list", compact("pageData"));
    }

    public function create()
    {
        $pageData = $this->getDataForPageRender();

        return view("admin.extensions.create", compact("pageData"));
    }

    public function store()
    {
        $data = request()->validate([
            "title" => ["required", "string", "max:64", "unique:extensions,title"],
            "code" => ["required", "string", "min:6", "max:6", "unique:extensions,code"],
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
        $extension = Extension::create($data);

        return redirect("/admin/extensions/$extension->id");
    }

    public function edit(Extension $extension) {
        $pageData = $this->getDataForPageRender();

        return view("admin.extensions.edit", compact("pageData", "extension"));
    }

    public function update(Extension $extension) {
        $data = request()->validate([
            "title" => ["required", "string", "max:64", Rule::unique("extensions", "title")->ignore($extension->id)],
            "code" => ["required", "string", "min:6", "max:6", Rule::unique("extensions", "code")->ignore($extension->id)],
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
                $extension->thumbnail,
                [],
                "public"
            );
        }

        $extension->update($data);

        return redirect("/admin/extensions/$extension->id");
    }

    public function addImage(Extension $extension)
    {
        $pageData = $this->getDataForPageRender();

        return view("admin.extensions.add-image", compact("pageData", "extension"));
    }

    public function storeImage(Extension $extension)
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

        ExtensionImage::create([
            "extension_id" => $extension->id,
            "image" => $image
        ]);

        return redirect("/admin/extensions/$extension->id");
    }

    public function confirmDeletion(Extension $extension)
    {
        $pageData = $this->getDataForPageRender();
        $heading = "Delete extension";
        $links = [
            "yes" => "/admin/extensions/$extension->id/delete/confirmed",
            "no" => "/admin/extensions/$extension->id"
        ];

        return view("shared.confirm-delete", compact("pageData", "heading", "links"));
    }

    public function delete(Extension $extension)
    {
        foreach ($extension->gallery as $extensionImage) {
            $this->deleteImage($this->PUBLIC_DIRECTORIES[1], $extensionImage->image);
        }

        $this->deleteImage($this->PUBLIC_DIRECTORIES[0], $extension->thumbnail);
        $extension->delete();

        return redirect("/admin/extensions");
    }
}
