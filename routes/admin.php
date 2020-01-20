<?php

/*
| Admin Routes
*/

Route::group(['namespace' => 'Admin'], function(){

    Route::post('login', 'LoginController@login');

    Route::group(['middleware' => ['passport-administrators', 'client:blog-admin']], function() {

        Route::get('info', 'LoginController@info');

        Route::group(['prefix' => 'admin'], function(){
            Route::get('options', 'AdminController@options');
        });

        Route::group(['prefix' => 'article'], function(){
            Route::get('list', 'ArticleController@list');
            Route::get('detail/{id}', 'ArticleController@detail');
            Route::get('category', 'ArticleController@category');
        });

    });

});

