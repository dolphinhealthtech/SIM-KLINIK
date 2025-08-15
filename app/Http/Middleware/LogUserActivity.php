<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check()) {
            DB::table('user_activity_logs')->insert([
                'user_id'    => Auth::id(),
                'username'   => Auth::user()->username ?? null, // Simpan username jika user_id tidak ada
                'activity'   => $request->path(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url'        => $request->fullUrl(),
                'method'     => $request->method(),
                'payload'    => json_encode($request->except(['password', '_token'])),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $response;
    }
}
