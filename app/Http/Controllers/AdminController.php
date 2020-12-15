<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\Admin;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

use App\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", Admin::class]);
    }

    public function userList()
    {
        $pageData = $this->getDataForPageRender();

        return view("admin.user-list", compact("pageData"));
    }

    public function userDetails()
    {
        $data = request()->validate([
            "id" => ["nullable"]
        ]);

        $pageData = $this->getDataForPageRender();
        $retArr = compact("pageData");

        if (array_key_exists("id", $data)) {
            $retArr = array_merge($retArr, [
                "start" => $data["id"]
            ]);
        }

        return view("admin.user-details", $retArr);
    }

    public function updateUser()
    {
        $data = request()->validate([
            "id" => ["required", "exists:users"],
            "username" => ["required", "string", "max:32"],
            "email" => ["required", "string", "email", "max:64", Rule::unique('users', 'email')->ignore(request("id"))],
            "picture" => ["nullable", "image"],
            "premium_expiration" => ["nullable", "date"],
            "darkmode" => ["nullable"],
            "admin" => ["nullable"]
        ]);

        $user = User::find($data["id"]);
        $data["darkmode"] = array_key_exists("darkmode", $data);
        $data["admin"] = $data["id"] != 1 ? array_key_exists("admin", $data) : true;

        // Update picture
        if (array_key_exists("picture", $data)) {
            $img = Image::make($data["picture"])->fit(512, 512);

            do {
                $fileName = Str::random(50) . "." . $data["picture"]->extension();
            } while (Storage::disk("ibm-cos")->has("profile_pictures/" . $fileName));

			Storage::disk("ibm-cos")->delete("profile_pictures/" . $user->profile_picture);
            Storage::disk("ibm-cos")->put("profile_pictures/" . $fileName, $img->stream());

            unset($data["picture"]);
            $data["profile_picture"] = $fileName;
        }

        $user->update($data);

        return redirect("/admin/user/details?id=" . $data["id"]);
    }
}
