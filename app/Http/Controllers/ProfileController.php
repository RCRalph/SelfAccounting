<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\User;

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
            "email" => ["required", "string", "email", "max:64", Rule::unique('users', 'email')->ignore(auth()->user()->id)],
            "picture" => ["nullable", "image"]
        ]);

        if (array_key_exists("picture", $data)) {
            $img = Image::make($data["picture"])->fit(512, 512);

            do {
                $fileName = Str::random(50) . "." . $data["picture"]->extension();
            } while (Storage::disk("ibm-cos")->has("profile_pictures/" . $fileName));

			Storage::disk("ibm-cos")->delete("profile_pictures/" . auth()->user()->profile_picture);
            Storage::disk("ibm-cos")->put("profile_pictures/" . $fileName, $img->stream());

            auth()->user()->update([
                "username" => $data["username"],
                "email" => $data["email"],
                "profile_picture" => $fileName
            ]);
        }
        else {
            auth()->user()->update([
                "username" => $data["username"],
                "email" => $data["email"]
            ]);
        }

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
}
