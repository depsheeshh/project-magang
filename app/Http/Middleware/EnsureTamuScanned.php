<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTamuScanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // cek session flag
        if (!$request->session()->get('tamu_scanned')) {
            return redirect()->route('tamu.scan')
                ->with('error', 'Silakan scan QR code terlebih dahulu.');
        }

        return $next($request);
    }
}
