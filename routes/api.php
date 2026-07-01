<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminStockController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminBranchController;
use App\Http\Controllers\Admin\AdminVehicleController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserStockController;
use App\Http\Controllers\User\UserCustomerController;
use App\Http\Controllers\User\UserBranchController;
use App\Http\Controllers\User\UserVehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Admin API Routes
Route::prefix('admin')->group(function () {
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('login', [AdminAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('profile', [AdminAuthController::class, 'profile']);

        Route::apiResource('stocks', AdminStockController::class);
        Route::apiResource('customers', AdminCustomerController::class);
        Route::apiResource('branches', AdminBranchController::class);
        Route::apiResource('vehicles', AdminVehicleController::class);
    });
});

// User API Routes
Route::prefix('user')->group(function () {
    Route::post('login', [UserAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
        Route::post('logout', [UserAuthController::class, 'logout']);
        Route::get('profile', [UserAuthController::class, 'profile']);

        // Stock User Operations (no update/delete)
        Route::get('stocks', [UserStockController::class, 'index']);
        Route::post('stocks', [UserStockController::class, 'store']);
        Route::get('stocks/{id}', [UserStockController::class, 'show']);

        // Customer User Operations (no update/delete)
        Route::get('customers', [UserCustomerController::class, 'index']);
        Route::post('customers', [UserCustomerController::class, 'store']);
        Route::get('customers/{id}', [UserCustomerController::class, 'show']);

        Route::apiResource('branches', UserBranchController::class);
        Route::apiResource('vehicles', UserVehicleController::class);
    });
});
