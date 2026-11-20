<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiteIsWritable
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (filter_var(env('SITE_READ_ONLY', false), FILTER_VALIDATE_BOOLEAN)) {
            abort(503, 'Le site est en lecture seule.');
        }

        return $next($request);
    }
}
