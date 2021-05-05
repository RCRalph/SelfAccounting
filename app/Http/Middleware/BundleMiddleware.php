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
        $bundle = Bundle::where("code", $bundleCode)->first();

        $USER = auth()->user();
        if (!($USER->bundles->contains($bundle) || $USER->premiumBundles->contains($bundle))) {
            return redirect("/bundles/$bundle->id");
        }

        return $next($request);
    }
}
