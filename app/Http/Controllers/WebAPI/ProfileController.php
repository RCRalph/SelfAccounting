<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $data = auth()->user()->only("profile_picture_link");

        return response()->json(compact("data"));
    }

    public function profilePicture()
    {
        $picture = request()->validate([
            "picture" => ["required", "image", "mimes:jpeg,png,jpg,bmp"]
        ])["picture"];

        $filename = $this->saveImage(
            "s3", $picture, config("constants.directories.cloud.profile_picture"),
            fileToRemove: auth()->user()->profile_picture,
            fitResolution: [512, 512]
        );

        auth()->user()->update(["profile_picture" => $filename]);
        Cache::forget("app-data-" . auth()->user()->id);

        return response()->json(["picture" => getFileLink("s3", config("constants.directories.cloud.profile_picture"), $filename)]);
    }
}
