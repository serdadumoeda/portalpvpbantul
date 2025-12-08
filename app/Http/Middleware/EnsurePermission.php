<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if (empty($permissions) || $user->hasAnyPermission($permissions)) {
            return $next($request);
        }

        abort(403);
    }
}
