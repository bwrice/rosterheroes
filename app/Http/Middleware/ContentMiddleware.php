<?php

namespace App\Http\Middleware;

use Closure;

class ContentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! app()->environment('local')) {
            abort(403, "Content creation must be done in local environment");
        }
        return $next($request);
    }
}
