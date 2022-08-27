<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    Route::get('/', function() {
        return responseJson(1, 'Task-Manager is running');
    });

    Route::prefix('user')->group(function () {
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login')->name('login');

        Route::middleware('auth:api')->group(function () {
            Route::post('logout', 'AuthController@logout');
        });
    });

    Route::prefix('projects')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::get('all', 'ProjectController@index');
            Route::post('create', 'ProjectController@create');
            Route::post('update/{id}', 'ProjectController@update');
            Route::post('delete/{id}', 'ProjectController@delete');
        });
    });

    Route::prefix('tasks')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::post('all', 'TaskController@index');
            Route::post('create', 'TaskController@create');
            Route::post('userTasks', 'TaskController@userTasks');
            Route::post('update/{id}', 'TaskController@update');
            Route::post('delete/{id}', 'TaskController@delete');
        });
    });
});
