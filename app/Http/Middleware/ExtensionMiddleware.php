<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Extension;

class ExtensionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $extensionCode = null)
    {
        if ($request->route("code")) {
            if (Extension::all()->pluck("code")->doesntContain($request->route("code"))) {
                abort(404, "Extension doesn't exist");
            }
            else {
                $extension = Extension::where("code", $request->route("code"))->first();
            }
        }
        else if ($extensionCode) {
            if (auth()->user()->extensionCodes->doesntContain($extensionCode)) {
                abort(403, "Invalid permission to access extension");
            }
            else {
                $extension = Extension::where("code", $request->route("code"))->first();
            }
        }
        else {
            abort(500, "Invalid extension");
        }

        $request->merge(compact("extension"));

        return $next($request);
    }
}
