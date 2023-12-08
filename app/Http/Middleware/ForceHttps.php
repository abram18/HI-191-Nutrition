<?php
namespace App\Http\Middleware;

use Closure;

class ForceHttps
{
    public function handle($request, Closure $next)
    {
        // Check if the request is not secure (not using HTTPS)
        if (!$request->secure()) {
            // Redirect to the HTTPS version of the URL
            return redirect()->secure($request->path());
        }

        // Continue with the request
        return $next($request);
    }
}