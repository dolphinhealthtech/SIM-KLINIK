<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Jenssegers\Agent\Agent; // Untuk mendeteksi browser, OS, device
use App\Models\Logs_app; // Model log activity
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Logs_app_Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true); // Waktu mulai eksekusi

        try {
            // Jalankan request
            $response = $next($request);
        } finally {
            // Tangkap user (web/session atau api guard)
            $user = Auth::user() ?? Auth::guard('api')->user();

            $agent = new Agent();
            $agent->setUserAgent($request->userAgent());

            $executionTime = microtime(true) - $startTime;

            try {
                Logs_app::create([
                    'user_id'    => $user->id ?? null,
                    'username'   => $user->username ?? "System",
                    'activity'   => $request->path(),
                    'ip_address' => $request->ip(),
                    'browser'    => $agent->browser(),
                    'os'         => $agent->platform(),
                    'device'     => $agent->device() ?: 'Desktop',
                    'is_api'     => str_starts_with($request->path(), 'api/') ? 'Yes' : 'No',
                    'method'     => $request->method(),
                    'time'       => now()->format('H:i:s'),
                    'response_status' => $response->getStatusCode(),
                    'payload'    => json_encode($request->except(['password', '_token'])),
                    'response_status' => $response->getStatusCode() ?? null,
                    'execution_ms'   => round($executionTime * 1000, 2),
                    'created_at' => now(),
                ]);
            } catch (\Throwable $e) {
                // Log error kalau gagal menyimpan log
                Log::error('Logs_app_Middleware gagal menyimpan log', [
                    'error' => $e->getMessage(),
                    'path'  => $request->path(),
                ]);
            }
        }

        return $response;
    }
}
