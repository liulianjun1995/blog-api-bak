<?php

/*
| Admin Routes
*/

Route::group(['namespace' => 'Admin'], function(){

    Route::post('login', 'LoginController@login');

    Route::group(['middleware' => ['passport-administrators', 'client:blog-admin']], function() {

        Route::get('user', 'LoginController@user');

        Route::group(['prefix' => 'post'], function(){
            Route::get('list', 'UserController@user');
        });

    });

});

