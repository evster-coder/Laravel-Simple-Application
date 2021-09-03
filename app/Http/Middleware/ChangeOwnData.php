<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Author;

class ChangeOwnData
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
        //find editing author
        $user = Author::find(request()->route()->parameter('id'));

        if($user == null)
            return response()->json([
                'success' => false,
                'message' => 'Wrong author id'
            ]);

        //check if user can modify this record
        if(Auth::check() && Auth::user()->id == $user->id)
            return $next($request);
        else
            return response()->json([
                    'success' => false,
                    'message' => 'Forbidden'
                ], 403);
    }
}
