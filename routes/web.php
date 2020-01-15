<?php

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

Route::get('/{path}', function () {
    return view('index');
})->where('path', '^((?!admin|oauth).)*$')->middleware(['IpLog']);

Route::group(['prefix' => 'oauth'], function () {
    Route::get('github', 'OAuthController@github');
    Route::get('github/login', 'OAuthController@githubLogin');

    Route::get('qq', 'OAuthController@qq');
    Route::get('qq/login', 'OAuthController@qqLogin');

    Route::get('wechat', 'OAuthController@wechat');
    Route::get('wechat/login', 'OAuthController@wechatLogin');
});

