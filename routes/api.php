<?php

use App\Http\Controllers\AdminBooksController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1'], function () {

    Route::post('/login', [AuthenticationController::class, 'index']);

    Route::group(['middleware' => 'auth:api'], function () {

        Route::group(['prefix' => 'admin/books'], function () {
            Route::post('/list', [AdminBooksController::class, 'booksList']);
            Route::post('/store', [AdminBooksController::class, 'storeBook']);
            Route::post('/get', [AdminBooksController::class, 'editBook']);
            Route::post('/update', [AdminBooksController::class, 'updateBook']);
            Route::post('/delete', [AdminBooksController::class, 'deleteBook']);
        });

        Route::get('/logout', [AuthenticationController::class, 'logout']);
    });

    Route::group(['prefix' => 'books'], function() {
        Route::get('/insertData', [BooksController::class, 'insert']);
        Route::post('/list', [BooksController::class, 'bookList']);
        Route::post('/get', [BooksController::class, 'getBook']);
    });

});
