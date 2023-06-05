<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CurrencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->route("currency")) {
            if (auth()->user()->currencies()->where("id", $request->route("currency")->id)->exists()) {
                auth()->user()->currencies()->updateExistingPivot(
                    $request->route("currency")->id,
                    [ "last_used" => now() ]
                );
            } else {
                auth()->user()->currencies()->attach(
                    $request->route("currency")->id,
                    [ "last_used" => now() ]
                );
            }
        }

        return $next($request);
    }
}
