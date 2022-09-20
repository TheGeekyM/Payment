<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequestsAcceptJson
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $acceptHeader = strtolower($request->headers->get('accept'));

        // If the accept header is not set to application/json
        // We attach it and continue the request
        if ($acceptHeader !== 'application/json') {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
