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

    public function gettingStarted()
    {
        $pageData = $this->getDataForPageRender();
        $text = $this->getTutorial("getting-started.md");

        return view("getting-started", compact("pageData", "text"));
    }
}
