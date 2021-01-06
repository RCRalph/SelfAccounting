<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Admin;
use Illuminate\Validation\Rule;

use App\User;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "admin"]);
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
            $data["profile_picture"] = $this->uploadImage(
                $data["picture"],
                $this->directories["profile-picture"],
                $user->profile_picture,
                [512, 512]
            );
            unset($data["picture"]);
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
        $heading = "Delete user";
        $links = [
            "yes" => "/admin/user/$user->id/delete/confirmed",
            "no" => "/admin/user/$user->id"
        ];

        return view("shared.confirm-delete", compact("pageData", "heading", "links"));
    }

    public function delete(User $user)
    {
        if ($user->id != 1) {
            $user->delete();
        }
        return redirect("/admin/users");
    }
}
