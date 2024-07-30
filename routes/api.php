<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return response()->json(['message' => 'INAIA Trading API.']);
});

Route::get('/test', function (Request $request) {
    return response()->json(['message' => 'INAIA Trading API. test']);
})->middleware('auth:sanctum');

Route::group(['prefix' => 'api/v1'], function () {
    Route::get('/', function (Request $request) {
        return response()->json(['message' => 'INAIA Trading API.']);
    });

    Route::post('/login', 'UserController@login')->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('user', 'UserController');
    });

    Route::group(['prefix' => 'trading-user'], function () {
        Route::post('/', 'TradingUserController@store');

        Route::group(['prefix' => 'check'], function () {
            Route::post('/{trading_user_id}/kyc', 'TradingUserCheckController@createKYCCheck');
            Route::post('/{trading_user_id}/if', 'TradingUserCheckController@createInstrumentFitCheck');
            Route::post('/{trading_user_id}/por', 'TradingUserCheckController@createProofOfResidencyCheck');
        });
    });

    Route::group(['prefix' => 'trading-account'], function () {

        Route::post('/', 'TradingAccountController@store');

        Route::group(['prefix' => 'group'], function () {
            Route::post('/', 'TradingAccountGroupController@store');
        });
    });

});
