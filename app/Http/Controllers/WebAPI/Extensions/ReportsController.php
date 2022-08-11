<?php

namespace App\Http\Controllers\WebAPI\Extensions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Rules\Common\SameLengthAs;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "extension:report"]);
    }

    public function index()
    {
        $owners = auth()->user()->sharedReports()
            ->select("users.id", "users.username")
            ->join("users", "reports.user_id", "=", "users.id")
            ->distinct()
            ->get()
            ->makeHidden("pivot");

        return response()->json(compact("owners"));
    }

    public function ownedReports()
    {
        $reports = auth()->user()->reports()
            ->select("users.username AS owner")
            ->select("id", "title");

        $data = request()->validate([
            "items" => ["required", "integer", "in:10,15,20,25,30"],
            "search" => ["nullable", "string", "min:1", "max:64"],
            "orderFields" => ["nullable", "array", new SameLengthAs("orderDirections")],
            "orderFields.*" => ["required", "string", "in:id,title", "distinct"],
            "orderDirections" => ["nullable", "array", new SameLengthAs("orderFields")],
            "orderDirections.*" => ["required", "string", "in:asc,desc"]
        ]);

        if (isset($data["search"])) {
            $reports->where("title", "ilike", "%" . $data["search"] . "%");
        }

        if (isset($data["orderFields"]) && isset($data["orderDirections"])) {
            foreach ($data["orderFields"] as $i => $item) {
                $reports = $reports->orderBy($item, $data["orderDirections"][$i] ?? "asc");
            }
        }

        $reports = $reports->paginate($data["items"]);

        return response()->json(compact("reports"));
    }

    public function sharedReports()
    {
        $reports = auth()->user()->sharedReports()
            ->select("reports.id", "reports.title", "users.username AS owner")
            ->join("users", "reports.user_id", "=", "users.id");

        $data = request()->validate([
            "items" => ["required", "integer", "in:10,15,20,25,30"],
            "search" => ["nullable", "string", "min:1", "max:64"],
            "owners" => ["nullable", "array"],
            "owners.*" => ["required", "integer", "exists:users,id"],
            "orderFields" => ["nullable", "array", new SameLengthAs("orderDirections")],
            "orderFields.*" => ["required", "string", "in:id,title", "distinct"],
            "orderDirections" => ["nullable", "array", new SameLengthAs("orderFields")],
            "orderDirections.*" => ["required", "string", "in:asc,desc"]
        ]);

        if (isset($data["search"])) {
            $reports->where("title", "ilike", "%" . $data["search"] . "%");
        }

        if (isset($data["owners"])) {
            $reports->whereIn("users.user_id", $data["owners"]);
        }

        if (isset($data["orderFields"]) && isset($data["orderDirections"])) {
            foreach ($data["orderFields"] as $i => $item) {
                $reports = $reports->orderBy($item, $data["orderDirections"][$i] ?? "asc");
            }
        }

        $reports = $reports->paginate($data["items"]);

        return response()->json(compact("reports"));
    }
}
