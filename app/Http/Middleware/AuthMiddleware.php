<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {


        if (!auth()->check()) {
            return redirect()->route('login');;
        }

        $userFlag = auth()->user()->user_flg;

        if (in_array($userFlag, $roles)) {
            return $next($request);
        }

        abort(403);
    }


}
