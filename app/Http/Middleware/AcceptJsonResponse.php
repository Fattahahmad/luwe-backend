<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AcceptJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only force Accept header if not already set to application/json
        if (!$request->headers->has('Accept') || $request->headers->get('Accept') !== 'application/json') {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
