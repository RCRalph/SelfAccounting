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

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", Admin::class]);
    }

    public function list()
    {
        $pageData = $this->getDataForPageRender();

        return view("admin.users.list", compact("pageData"));
    }

    public function details(User $user)
    {
        $pageData = $this->getDataForPageRender();

        return view("admin.users.details", compact("pageData", "user"));
    }

    public function update(User $user)
    {
        $data = request()->validate([
            "username" => ["required", "string", "max:32"],
            "email" => ["required", "string", "email", "max:64", Rule::unique('users', 'email')->ignore($user->id)],
            "picture" => ["nullable", "image"],
            "premium_expiration" => ["nullable", "date"],
            "darkmode" => ["nullable"],
            "admin" => ["nullable"]
        ]);

        $data["darkmode"] = array_key_exists("darkmode", $data);
        $data["admin"] = $user->id != 1 ? array_key_exists("admin", $data) : true;

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

        return redirect("/admin/users/$user->id");
    }

    public function confirmDeletion(User $user)
    {
        if ($user->id == 1) {
            return redirect("/admin/users/1");
        }

        $pageData = $this->getDataForPageRender();
        $links = [
            "yes" => "/admin/user/$user->id/delete/confirmed",
            "no" => "/admin/user/$user->id"
        ];

        return view("shared.confirm-delete", compact("pageData", "links"));
    }

    public function delete(User $user)
    {
        if ($user->id != 1) {
            $user->delete();
        }
        return redirect("/admin/users");
    }
}
