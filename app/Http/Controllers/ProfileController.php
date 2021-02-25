<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $pageData = $this->getDataForPageRender();

        return view("profile.index", compact("pageData"));
    }

    public function updateData()
	{
        $data = request()->validate([
            "username" => ["required", "string", "max:32"],
            "email" => ["required", "string", "email", "max:64", Rule::unique("users", "email")->ignore(auth()->user()->id)],
            "picture" => ["nullable", "image"]
        ]);

        // Update picture
        if (array_key_exists("picture", $data)) {
            $data["profile_picture"] = $this->uploadImage(
                $data["picture"],
                $this->directories[2],
                auth()->user()->profile_picture,
                [512, 512]
            );
            unset($data["picture"]);
            $id = auth()->user()->id;
            Cache::forget("page-render-data-$id");
        }

        auth()->user()->update($data);

        return redirect("/profile");
    }

    public function updatePassword()
    {
        $data = request()->validate([
            "password" => ["required", "string", "min:8", "confirmed"]
        ]);

        auth()->user()->update([
            "password" => Hash::make($data["password"])
        ]);

        return redirect("/profile");
    }

    public function confirmDeletion()
    {
        if (auth()->user()->id == 1) {
            return redirect("/profile");
        }

        $pageData = $this->getDataForPageRender();
        $heading = "Delete profile";
        $links = [
            "yes" => "/profile/delete/confirmed",
            "no" => "/profile"
        ];

        return view("shared.confirm-delete", compact("pageData", "heading", "links"));
    }

    public function delete()
    {
        if (auth()->user()->id == 1) {
            return redirect("/profile");
        }

        $this->deleteImage($this->directories[2], auth()->user()->profile_picture);
        auth()->user()->delete();

        return redirect("/");
    }
}
