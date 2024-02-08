<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Extension;

class ExtensionsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $boughtExtensions = auth()->user()->extensions()
            ->select("code", "enabled")
            ->get()
            ->mapWithKeys(fn($item) => [$item["code"] => $item["enabled"]]);

        $premiumExtensions = auth()->user()->premiumExtensions->pluck("code");

        $extensions = Extension::select(
            "id", "code", "title", "icon", "directory", "thumbnail", "short_description", "description", "price"
        )
            ->with("gallery")
            ->orderBy("title")
            ->get()
            ->map(fn($item) => [
                "id" => $item->id,
                "code" => $item->code,
                "title" => $item->title,
                "icon" => $item->icon,
                "directory" => $item->directory,
                "thumbnail" => $item->thumbnail_link,
                "short_description" => $item->short_description,
                "description" => Str::markdown($item->description_text),
                "bought" => $boughtExtensions->has($item->code),
                "enabled" => $boughtExtensions->has($item->code) ?
                    $boughtExtensions->get($item->code) :
                    $premiumExtensions->contains($item->code),
                "price" => $item->price * 1,
                "gallery" => $item->gallery->pluck("image_link"),
            ]);

        return response()->json(compact("extensions"));
    }

    public function toggle()
    {
        $this->authorize("toggle", request()->extension);

        // When user owns given extension
        if (auth()->user()->extensions()->pluck("id")->contains(request()->extension->id)) {
            $currentlyEnabled = auth()->user()->extensions()
                ->find(request()->extension)
                ->pivot->enabled;

            auth()->user()->extensions()
                ->updateExistingPivot(
                    request()->extension,
                    ["enabled" => !$currentlyEnabled]
                );
        } else {
            auth()->user()->premiumExtensions()->toggle(request()->extension);
        }

        return response("");
    }
}
