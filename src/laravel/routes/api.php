<?php

use Illuminate\Http\Request;

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');

    Route::put('user', 'AuthController@store');
    Route::post('user', 'AuthController@edit');
    Route::delete('user', 'AuthController@delete');
    Route::get('user', 'AuthController@me');
});