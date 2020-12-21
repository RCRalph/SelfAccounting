<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

use App\User;
use App\Bundle;

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

        $data["short_description"] = clean($data["short_description"]);
        $data["description"] = clean($data["description"]);

        // Upload image
        $img = Image::make($data["thumbnail"]);
        do {
            $fileName = Str::random(50) . "." . $data["thumbnail"]->extension();
        } while (Storage::disk("ibm-cos")->has("bundles/thumbnails/" . $fileName));

        Storage::disk("ibm-cos")->put("bundles/thumbnails/" . $fileName, $img->stream());
        $data["thumbnail"] = $fileName;

        Bundle::create($data);

        return redirect("/admin/bundles");
    }
}
