<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //check if user has admin permissions
        if(Auth::check() && Auth::user()->admin)
            return $next($request);
        else
            return response()->json([
                    'success' => false,
                    'message' => 'Forbidden'
                ], 403);
    }
}
