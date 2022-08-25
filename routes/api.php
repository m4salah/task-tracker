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
    });
});

