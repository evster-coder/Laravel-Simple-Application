<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Book;

class ChangeOwnBooks
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
        //find editing book
        $book = Book::find(request()->route()->parameter('id'));

        if($book == null)
            return response()->json([
                'success' => false,
                'message' => 'Wrong book id'
            ]);

        //check if user can modify this record
        if(Auth::check() && Auth::user()->id == $book->author_id)
            return $next($request);
        else
            return response()->json([
                    'success' => false,
                    'message' => 'Forbidden'
                ], 403);
    }
}
