<?php

use Illuminate\Http\Request;

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('create', 'AuthController@store');
    Route::post('edit', 'AuthController@edit');
    Route::post('delete', 'AuthController@delete');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});