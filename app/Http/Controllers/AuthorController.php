<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Author;

class AuthorController extends Controller
{
    /**
     * Display a listing of the authors.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authors = Author::all();
        return response()->json($authors);
    }

    /**
     * Store a newly created author in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //its better to validate data in BookRequest (todo)
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:authors,username',
            'password' => 'required|string|min:3',
        ]);

        $author = new Author([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'admin' => false,
        ]);
        $author->save();

        $response = [
            'success' => true,
            'message' => 'New author was added',
        ];
        return response()->json($response);

    }

    /**
     * Display the specified author.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $author = Author::find($id);

        if($author)
            return response()->json([
                'success' => true,
                'author' => $author,
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Wrong author id',
            ]);
    }

    public function showBooks($id)
    {
        $author = Author::find($id);

        if($author)
            return response()->json([
                'success' => true,
                'author' => Author::where('id', $id)
                            ->with('Books')
                            ->get(),
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Wrong author id',
            ]);

    }
    
    /**
     * Update the specified author in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //its better to validate data in BookRequest (todo)
        $request->validate([
            'name' => 'sometimes|string',
            'username' => 'sometimes|string|unique:authors,username,' . $id,
            'password' => 'sometimes|string|min:3'
        ]);


        $author = Author::find($id);

        if($author)
        {
            $result = $author->fill($request->input());

            if($request->has('password'))
                $result->password = Hash::make($request->password);
            $result->save();

            if($result)
                return response()->json([
                    'success' => true,
                    'message' => 'Author was successfully updated',
                ]);
            else
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating author',
                ]);
        }
        else
            return response()->json([
                'success' => false,
                'message' => 'Wrong author id',
            ]);

    }

    /**
     * Remove the specified author from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $author = Author::find($id);

        //author found and not admin
        //you cant destroy admin user
        if($author && !$author->admin)
        {
            $author->delete();
            return response()->json([
                'success' => true,
                'message' => 'Author was successfully destroyed',
            ]);
        }
        else
            return response()->json([
                'success' => false,
                'message' => 'Wrong author id',
            ]);
    }


    /**
     *Return all authors with amount of books for each one
     *
     *
     */
    public function amountBooksList()
    {
        //select  important fields from author and 
        //count function from  left join 'books' table
        $items = Author::select('authors.id', 'authors.name', 
                                'authors.username',
                                DB::raw('COUNT(books.id) as booksamount'))
                        ->leftJoin('books', 
                                'authors.id', '=', 'books.author_id')
                        ->groupBy('authors.id')
                        ->orderBy('booksamount', 'desc')
                        ->get();

        //old solution (has extra fields)
        //$sorted = Author::all()
        //            ->sortByDesc('booksamount')
        //            ->values()
        //            ->all();

        return response()->json($items);
    }
}
