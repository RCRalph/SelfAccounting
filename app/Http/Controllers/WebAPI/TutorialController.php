<?php

namespace App\Http\Controllers\WebAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\Tutorial;

class TutorialController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $route = request()->validate([
            "route" => ["required", "string", "max:128", "exists:tutorials,route"]
        ])["route"];

        $tutorial = Tutorial::where("route", $route)->first();
        $tutorialText = Storage::disk("local")->get("/files/tutorials/$tutorial->filename");

        if (!$tutorialText) {
            abort(404, "Tutorial doesn't exist");
        }

        $tutorialText = explode(" ", $tutorialText);

        // Add no-break spaces to tutorial text
        for ($i = count($tutorialText) - 2; $i >= 0; $i--) {
            if (strlen($tutorialText[$i]) == 1 && !in_array($tutorialText[$i], ["#", "-"])) {
                $tutorialText[$i + 1] = $tutorialText[$i] . "&nbsp;" . $tutorialText[$i + 1];
                array_splice($tutorialText, $i, 1);
                $i++;
            }
        }

        return response()->json([
            "tutorial" => Str::markdown(
                implode(" ", $tutorialText),
                [
                    "html_input" => "strip"
                ]
            )
        ]);
    }

    public function hide()
    {
        $route = request()->validate([
            "route" => ["required", "string", "max:128", "exists:tutorials,route"]
        ])["route"];

        $tutorial = Tutorial::where("route", $route)->first();

        if (!auth()->user()->disabledTutorials()->where("tutorial_id", $tutorial->id)->exists()) {
            auth()->user()->disabledTutorials()->attach($tutorial);
        }

        return response("");
    }

    public function hideAll()
    {
        auth()->user()->update(["hide_all_tutorials" => true]);

        return response("");
    }
}
