<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('me', 'AuthController@me');
});

Route::middleware('VerifyJwtMiddleware')->group(function () {
    Route::group([
        'prefix' => 'tasks',
        'namespace' => 'App\Http\Controllers'
    ], function () {
        Route::get('/', 'TaskController@index');
        Route::post('/', 'TaskController@store');
        Route::get('/{id}', 'TaskController@show');
        Route::put('/{id}', 'TaskController@update');
        Route::delete('/{id}', 'TaskController@destroy');
    });
});
