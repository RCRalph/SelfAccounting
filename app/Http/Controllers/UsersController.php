<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function darkmode()
    {
        $data = request()->validate([
            "darkmode" => ["boolean"]
        ]);

        auth()->user()->update([
            "darkmode" => $data["darkmode"]
        ]);

        return response()->json([]);
    }
}
