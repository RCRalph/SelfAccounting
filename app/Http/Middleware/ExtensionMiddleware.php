<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Extension;

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
                abort(404);
            }
            else {
                $extension = Extension::where("code", $request->route("code"))->first();
            }
        }
        else if ($extensionCode) {
            if (auth()->user()->extensionCodes->doesntContain($extensionCode)) {
                abort(403);
            }
            else {
                $extension = Extension::where("code", $request->route("code"))->first();
            }
        }
        else {
            abort(500);
        }

        $request->merge(compact("extension"));

        return $next($request);
    }
}
