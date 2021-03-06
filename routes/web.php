<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/', 'PatternController@parseString');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/books', 'BookController')->only(['store', 'show', 'destroy'])->middleware('auth');
Route::post('/books/update-books-order', 'BookController@updateBooksSortOrderInBulk')->middleware(['auth']);