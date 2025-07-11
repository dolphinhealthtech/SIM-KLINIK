<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class LogExecutionTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        $time = round((microtime(true) - $start) * 1000, 2);
        $route = $request->route()?->uri() ?? 'unknown';

        Log::info("⏱️ Route [{$route}] took {$time} ms");

        return $response;
    }
}
