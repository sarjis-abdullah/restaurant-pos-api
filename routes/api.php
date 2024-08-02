<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Restaurant API working']);
});

Route::group(['prefix' => 'api/v1'], function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Hello from Restaurant POS API.']);
    });

    Route::post('/login', 'UserController@login')->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('user', 'UserController');
    });
});
