<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RedirectIfAuthenticatedWithRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Admin, Frontliner, Pegawai → dashboard Stisla
            if ($user->hasRole('admin') || $user->hasRole('frontliner') || $user->hasRole('pegawai')) {
                return redirect()->route('dashboard.index');
            }


            // Tanpa role → landing page
            return redirect()->route('home');
        }

        return $next($request);
    }
}
