<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShareUserDetails
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $user = $request->user();
            inertia()->share('auth.user', [
                'id'=> $user->id,
                'name' => $user->name,
                'email'=> $user->email,
                'role' => $user->getRoleNames()->first(),
            ]);
        }

        return $next($request);
    }
}
