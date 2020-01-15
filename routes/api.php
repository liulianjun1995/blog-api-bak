<?php

use Illuminate\Http\Request;
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


Route::get('/carousels', 'Api\CarouselController@list');

Route::get('/posts', 'Api\PostController@index');
Route::get('/posts/recommend', 'Api\PostController@recommend');
Route::get('/posts/top', 'Api\PostController@top');
Route::get('/posts/hot', 'Api\PostController@hot');

Route::get('/post/{token}', 'Api\PostController@show');

Route::get('/comment/new', 'Api\CommentController@new');

Route::get('/blogrolls', 'Api\BlogrollController@index');
Route::get('/notices', 'Api\NoticeController@index');

Route::get('/categories', 'Api\CategoryController@list');
Route::get('/category/{name}', 'Api\CategoryController@articles');

Route::get('/tag/{name}', 'Api\TagController@articles');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('user', 'Api\UserController@user');

    Route::post('comment/post/{token}', 'Api\UserController@comment');

    Route::post('comment/reply/{token}', 'Api\UserController@reply');

    Route::post('praise/post/{token}', 'Api\UserController@praise');
});