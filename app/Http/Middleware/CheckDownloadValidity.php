<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDownloadValidity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    public function handle(Request $request, Closure $next)
    {
        $downloadValid = session('download_valid');
        $expiresAt = session('download_valid_expires_at');

        if ($downloadValid && now()->lessThan($expiresAt)) {
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'Download link expired or not valid.');
    }
}
