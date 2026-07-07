<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Unauthenticated.');
        }

        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            if ($request->expectsJson()) {
                return $next($request);
            }
            $allowedPaths = ['meeting/', 'meeting/*', 'audio-notulensi/', 'audio-notulensi/*', 'join', 'profile'];
            $path = $request->path();
            foreach ($allowedPaths as $allowed) {
                $pattern = '/^' . str_replace('\*', '.*', preg_quote($allowed, '/')) . '/';
                if (preg_match($pattern, $path)) {
                    return $next($request);
                }
            }
            if (!Str::startsWith($path, 'admin')) {
                return redirect('/admin');
            }
            return $next($request);
        }

        foreach ($permissions as $permission) {
            if (! $user->can(Str::snake($permission))) {
                return redirect()->route('profile.show')
                    ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
            }
        }

        return $next($request);
    }
}
