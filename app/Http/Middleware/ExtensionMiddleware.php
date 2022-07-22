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
    public function handle(Request $request, Closure $next, $extensionCode)
    {
        if (auth()->user()->extensionCodes->doesntContain($extensionCode)) {
            abort(403);
        }

        return $next($request);
    }
}
