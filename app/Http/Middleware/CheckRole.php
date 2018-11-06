<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {

        if (! $request->user()->hasRole($role)) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            
            return redirect('/');
        }

        return $next($request);
    }
}
