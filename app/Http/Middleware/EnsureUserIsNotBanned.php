<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return to_route('/');
        }

        if ($user->isNotBanned() && request()?->is('banned')) {
            return to_route('dashboard');
        }

        if ($user->isBanned() && !request()?->is('banned')) {
            return redirect()->route('banned')->with('error', 'Your account has been banned.');
        }

        return $next($request);
    }
}
