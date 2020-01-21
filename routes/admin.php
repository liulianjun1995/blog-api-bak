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
            Route::get('create', 'ArticleController@create');
            Route::post('update/{id}', 'ArticleController@update');
            Route::post('status/{id}', 'ArticleController@changeStatus');
            Route::post('upload-image', 'ArticleController@uploadImage');
            Route::post('delete-image', 'ArticleController@deleteImage');

            Route::get('tags', 'ArticleController@tagList');
            Route::get('tag/{id}', 'ArticleController@tagDetail');
            Route::post('tag/create', 'ArticleController@createTag');
            Route::post('tag/update/{id}', 'ArticleController@updateTag');
        });

    });

});

