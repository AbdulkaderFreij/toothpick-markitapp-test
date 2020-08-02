<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::get('/posts', 'PostController@index');
    Route::get('/post/{id}', 'PostController@show');
    Route::get('/posts/{idd}', 'PostController@showById');
    Route::put('/post/{id}', 'PostController@update');
    Route::post('/post', 'PostController@store');
    Route::delete('/post/{id}', 'PostController@destroy');

    Route::post('/logout', 'AuthController@logout');
});
