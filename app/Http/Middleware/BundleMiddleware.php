<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Bundle;

class BundleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $bundleCode)
    {
        if (auth()->user()->bundleCodes->doesntContain($bundleCode)) {
            abort(403);
        }

        return $next($request);
    }
}
