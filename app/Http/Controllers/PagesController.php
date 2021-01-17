<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function payment()
    {
        $data = request()->validate([
            "bundle" => ["nullable", "integer", "exists:bundles,id"]
        ]);
        $id = array_key_exists("bundle", $data) ? $data["bundle"] : 0;
        $userId = auth()->user()->id;

        $pageData = $this->getDataForPageRender();
        return view("payment", compact("pageData", "id", "userId"));
    }

    public function premium()
    {
        $pageData = $this->getDataForPageRender();
        return view("premium", compact("pageData"));
    }

    public function tutorial()
    {
        $pageData = $this->getDataForPageRender();
        $text = Storage::disk("local")->get("files/tutorial.md");

		// Add no-break spaces
        $text = explode(" ", $text);
        for ($i = count($text) - 2; $i >= 0; $i--) {
            if (strlen($text[$i]) == 1 && !in_array($text[$i], ["#", "-"])) {
                $text[$i + 1] = $text[$i] . "&nbsp;" . $text[$i + 1];
                array_splice($text, $i, 1);
                $i++;
            }
        }
        $text = implode(" ", $text);

        return view("tutorial", compact("pageData", "text"));
    }
}
