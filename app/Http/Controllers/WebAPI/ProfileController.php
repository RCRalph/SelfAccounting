<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

use App\Rules\Profile\SameAsCurrentPassword;
class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function expirationText(int $number, string $unit) {
        return "$number $unit" . ($number > 1 ? "s" : "") . " until expiration";
    }

    public function index()
    {
        $data = auth()->user()->only("profile_picture_link", "account_type", "email", "username", "darkmode", "hide_all_tutorials");

        $data["since"] = auth()->user()->created_at;

        if ($data["account_type"] == "Premium" && auth()->user()->premium_expiration) {
            $differenceInHours = auth()->user()->premium_expiration->endOfDay()->diffInHours(now());

            $data["expiration"] = $differenceInHours >= 24 ?
                $this->expirationText(intdiv($differenceInHours, 24), "day") :
                $this->expirationText($differenceInHours, "hour");
        }

        return response()->json(compact("data"));
    }

    public function updatePicture()
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

    public function updateInformation()
    {
        $data = request()->validate([
            "username" => ["required", "string", "max:32"],
            "email" => ["required", "string", "email", "max:64", Rule::unique("users", "email")->ignore(auth()->user()->id)]
        ]);

        auth()->user()->update($data);
        Cache::forget("app-data-" . auth()->user()->id);

        return response("");
    }

    public function updatePassword()
    {
        $data = request()->validate([
            "current_password" => ["required", "string", "min:8", "max:64", new SameAsCurrentPassword],
            "new_password" => ["required", "string", "min:8", "max:64", "confirmed"]
        ]);

        auth()->user()->update(["password" => Hash::make($data["new_password"])]);

        return response("");
    }

    public function updateSettings()
    {
        $data = request()->validate([
            "darkmode" => ["required", "boolean"],
            "show_tutorials" => ["required", "boolean"]
        ]);

        $data["hide_all_tutorials"] = !$data["show_tutorials"];
        unset($data["show_tutorials"]);

        auth()->user()->update($data);

        return response("");
    }

    public function destroy()
    {
        if (auth()->user()->admin) {
            abort(422, "Cannot delete admin");
        }

        $this->removeFile("s3",
            config("constants.directories.cloud.profile_picture"),
            auth()->user()->profile_picture
        );

        auth()->user()->delete();

        return response("");
    }
}
