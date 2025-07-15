<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleFormData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log untuk debugging
        \Log::info('HandleFormData middleware:', [
            'content_type' => $request->header('Content-Type'),
            'method' => $request->method(),
            'all' => $request->all(),
            'post' => $_POST,
            'input' => $request->input(),
            'raw_body' => $request->getContent(),
        ]);

        // Jika content-type adalah multipart/form-data dan data kosong
        if ($request->isMethod('POST') &&
            strpos($request->header('Content-Type'), 'multipart/form-data') !== false &&
            empty($request->all()) &&
            !empty($_POST)) {

            // Merge $_POST data ke request
            $request->merge($_POST);

            \Log::info('Merged POST data:', $request->all());
        }

        return $next($request);
    }
}
