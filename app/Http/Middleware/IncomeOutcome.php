<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IncomeOutcome
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $text = $request->route("viewType");

        if ($text != "income" && $text != "outcome") {
            abort(404);
        }

        return $next($request);
    }
}
