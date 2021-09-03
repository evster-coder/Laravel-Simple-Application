<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Routes for login and logout
Route::post('/login-admin', [AuthController::class, 'loginAdmin'])
		->name('login-admin');
Route::post('/login', [AuthController::class, 'login'])
		->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')
		->name('logout');


//Routes for rest api

//get books list with authors
Route::get('/books', [BookController::class, 'getAuthorList'])
			->name('books.index');

//get data about book by id
Route::get('/books/{id}', [BookController::class, 'show'])
			->name('books.show');

//get authors list with books amount for each
Route::get('/authors', [AuthorController::class, 'amountBooksList'])
			->name('authors.books');

//get data about author  with his books
Route::get('/authors/{id}', [AuthorController::class, 'showBooks'])
			->name('authors.showBooks');


Route::group(['middleware' => 'auth:sanctum'], function(){

	//only author can change his books
	Route::group(['middleware' => 'own-book'], function(){
		//update book only for its author
		Route::put('/books/{id}', [BookController::class, 'update'])
				->name('books.update');

		//destroy book by id
		Route::delete('/books/{id}', [BookController::class, 'destroy'])
				->name('books.destroy');
	});

	//update author data only for himself
	Route::put('/authors/{id}', [AuthorController::class, 'update'])
			->middleware(['own-author'])
			->name('authors.update');
});



//Routes for admin system 
//(need to forbid access for not admin users)
Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'admin']], function(){
	//routes for books crud
	Route::get('/books', [BookController::class, 'index'])
				->name('admin.books.index');
	Route::get('/books/{id}', [BookController::class, 'show'])
				->name('admin.books.show');
	Route::post('/books', [BookController::class, 'store'])
				->name('admin.books.store');
	Route::put('/books/{id}', [BookController::class, 'update'])
				->name('admin.books.update');
	Route::delete('/books/{id}', [BookController::class, 'destroy'])
				->name('admin.books.destroy');

	//routes for authors crud
	Route::get('/authors', [AuthorController::class, 'index'])
				->name('admin.authors.index');
	Route::get('/authors/{id}', [AuthorController::class, 'show'])
				->name('admin.authors.show');
	Route::post('/authors', [AuthorController::class, 'store'])
				->name('admin.authors.store');
	Route::put('/authors/{id}', [AuthorController::class, 'update'])
				->name('admin.authors.update');
	Route::delete('/authors/{id}', [AuthorController::class, 'destroy'])
				->name('admin.authors.destroy');

	//routes for getting list of author with amount of books each
	Route::get('/authors-books', [AuthorController::class, 'amountBooksList'])
				->name('admin.authors.books');
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
