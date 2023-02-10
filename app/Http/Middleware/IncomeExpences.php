<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IncomeExpences
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $type)
    {
        if (!in_array($type, ["income", "expences"])) {
            abort(404, "Invalid type");
        }

        $request->merge(compact("type"));

        return $next($request);
    }
}
