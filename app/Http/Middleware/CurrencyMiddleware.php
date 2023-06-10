<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

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
            $pivotData = [
                $request->route("currency")->id,
                [ "last_used" => now() ]
            ];

            if (auth()->user()->currencies()->where("id", $request->route("currency")->id)->exists()) {
                auth()->user()->currencies()->updateExistingPivot(...$pivotData);
            } else {
                try {
                    auth()->user()->currencies()->attach(...$pivotData);
                } catch (QueryException $e) {
                    if ($e->getCode() != 23505) throw $e;
                    /*  Code 23505 for Postgres means duplicate primary key. Sometimes this code
                        is thrown when multiple request are sent when using a currency for the
                        first time. To avoid this, a compound primary key is used. */
                }
            }
        }

        return $next($request);
    }
}
