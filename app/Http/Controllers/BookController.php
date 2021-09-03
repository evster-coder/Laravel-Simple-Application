<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Book;

class BookController extends Controller
{
    /**
     * Display a listing of the books.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::with('Author')->get();
        return response()->json($books);
    }


    /**
     * Return all books with author name.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAuthorList()
    {
        $books = Book::select('books.id', 'books.name', 
                'authors.name as authorName', 'books.publisher', 'books.year')
            ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
            ->get();

        return response()->json($books);
    }

    /**
     * Store a newly created book in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //its better to validate data in BookRequest (todo)
        $request->validate([
            'year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'name' => 'required|string',
            'publisher' => 'required|string',
            'author_id' => 'required',
        ]);

        if($request->has('author_id'))
            //request from admin
            $author = $request->author_id;
        else
            //request from api
            $author = Auth::user()->id;

        $book = new Book([
            'name' => $request->name,
            'author_id' => $author,
            'publisher' => $request->publisher,
            'year' => $request->year,
        ]);
        $book->save();

        $response = [
            'success' => true,
            'message' => 'New book was added',
        ];
        return response()->json($response);
    }

    /**
     * Display the specified book.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::where('id', $id)
                    ->with('Author')
                    ->first();

        if($book)
            return response()->json([
                'success' => true,
                'book' => $book,
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Wrong book id',
            ]);
    }

    /**
     * Update the specified book in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //its better to validate data in BookRequest (todo)
        $request->validate([
            'year' => 'sometimes|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'name' => 'sometimes|string',
            'publisher' => 'sometimes|string',
            'author_id' => 'sometimes',
        ]);
        
        $book = Book::find($id);

        if($book)
        {
            $result = $book->fill($request->all());

            $result->save();

            if($result)
                return response()->json([
                    'success' => true,
                    'message' => 'Book was successfully updated',
                ]);
            else
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating book',
                ]);
        }
        else
            return response()->json([
                'success' => false,
                'message' => 'Wrong book id',
            ]);
    }

    /**
     * Remove the specified book from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if($book)
        {
            $book->delete();
            return response()->json([
                'success' => true,
                'message' => 'Book was successfully destroyed',
            ]);
        }
        else
            return response()->json([
                'success' => false,
                'message' => 'Wrong book id',
            ]);
    }
}
