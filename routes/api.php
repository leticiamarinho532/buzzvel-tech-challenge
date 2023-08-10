<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::resource('tasks', TaskController::class)->except(['create', 'edit']);
Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('me', 'AuthController@me');
});
