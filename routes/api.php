<?php

use App\Enums\RolesAndPermissions;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Restaurant API working']);
});

Route::get('/install', function (Request $request) {
//    $user = \App\Models\User::where('id', '!=', null)->first();

    Artisan::call('migrate:fresh', array('--force' => true));
    Artisan::call('db:seed');
    Artisan::call('storage:link');

    define('STDIN',fopen("php://stdin","r"));
    return 'Installation completed successfully.';
});

Route::group(['prefix' => 'api/v1'], function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Hello from Restaurant POS API.']);
    });

    Route::post('/login', 'UserController@login')->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('user', 'UserController');
        Route::apiResource('company', 'CompanyController');
        Route::apiResource('branch', 'BranchController');
        Route::apiResource('floor', 'FloorController');
        Route::apiResource('table', 'TableController');
        Route::put('/table/{table}/book', 'TableController@bookTable')->name('book.table');
    });

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::middleware(
            ['role:'.RolesAndPermissions::ADMIN.'|'.RolesAndPermissions::OPERATOR.'|'.RolesAndPermissions::SUPER_ADMIN]
        )->group(function () {
//            Route::apiResource('membership', \App\Http\Controllers\MembershipController::class);
//            Route::apiResource('cash-flow', \App\Http\Controllers\CashFlowController::class);

//            Route::get('category', [\App\Http\Controllers\CategoryController::class, 'index']);
//            Route::get('floor', [\App\Http\Controllers\FloorController::class, 'index']);

//            Route::get('close-cash', [\App\Http\Controllers\CashFlowController::class, 'endDay']);
        });

        Route::middleware(
            ['role:'.RolesAndPermissions::ADMIN.'|'.RolesAndPermissions::SUPER_ADMIN]
        )->group(function () {

            Route::group(['prefix' => 'report'], function () {
                //  Route::get('/transaction', [\App\Http\Controllers\ReportController::class, 'getTransactionReport'])->name('transaction.report');
            });
            Route::apiResource('user', UserController::class);
            Route::apiResource('branch', \App\Http\Controllers\BranchController::class);
            Route::apiResource('floor', \App\Http\Controllers\FloorController::class);
            Route::apiResource('category', \App\Http\Controllers\CategoryController::class);
            Route::apiResource('discount', \App\Http\Controllers\DiscountController::class);
            Route::apiResource('tax', \App\Http\Controllers\TaxController::class);
            Route::apiResource('order', \App\Http\Controllers\OrderController::class);
//            Route::apiResource('membership-type', \App\Http\Controllers\MembershipTypeController::class);
        });
    });
});
