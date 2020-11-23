<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Admin;

use App\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", Admin::class]);
    }

    public function users()
    {
        $users = User::where("id", ">", "0")
            ->select("id", "email", "admin", "premium_expiration")
            ->orderBy("id")
            ->paginate(20);

        return response()->json(compact("users"));
    }

    public function changeAdmin()
    {
        $data = request()->validate([
            "id" => ["required", "exists:users", "not_in:1"],
            "status" => ["required", "boolean"]
        ]);

        User::find($data["id"])->update([
            "admin" => $data["status"]
        ]);

        return response()->json();
    }

    public function changePremium()
    {
        $data = request()->validate([
            "id" => ["required", "exists:users"],
            "date" => ["nullable", "date"]
        ]);


        User::find($data["id"])->update([
            "premium_expiration" => $data["date"]
        ]);

        return response()->json();
    }
}
