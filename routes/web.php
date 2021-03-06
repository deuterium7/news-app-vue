<?php

Route::middleware('guest')->group(function () {
    Route::get('/login/{social}/callback', 'AuthController@socialCallback');
    Route::get('/login/{social}', 'AuthController@socialRedirect');
    Route::get('/login', 'AppController@login')->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('/locale/{locale}', 'AppController@locale');
    Route::get('/logout', 'AuthController@logout');
    Route::get('/', 'AppController@app');

    Route::post('/contact', 'AppController@send');
});
