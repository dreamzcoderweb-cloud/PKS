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
use App\Http\Controllers\Admin\AdminUnitController;
use App\Http\Controllers\Admin\AdminAlternateUnitController;
use App\Http\Controllers\Admin\AdminBranchPriceController;
use App\Http\Controllers\Admin\AdminDealerController;
use App\Http\Controllers\User\UserUnitController;
use App\Http\Controllers\User\UserAlternateUnitController;
use App\Http\Controllers\User\UserBranchPriceController;
use App\Http\Controllers\User\UserDealerController;
use App\Http\Controllers\Admin\AdminTransporterController;
use App\Http\Controllers\User\UserTransporterController;
use App\Http\Controllers\Admin\AdminPurchaseController;
use App\Http\Controllers\User\UserPurchaseController;
use App\Http\Controllers\Admin\AdminSaleController;
use App\Http\Controllers\User\UserSaleController;
use App\Http\Controllers\Mob\Admin\AdminMobPurchaseController;
use App\Http\Controllers\Mob\Admin\AdminMobSaleController;
use App\Http\Controllers\Mob\User\UserMobPurchaseController;
use App\Http\Controllers\Mob\User\UserMobSaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::get('test',function(){
    return "Welcome to Api route";
});
// Admin API Routes
Route::prefix('admin')->group(function () {
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('login', [AdminAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('profile', [AdminAuthController::class, 'profile']);

        Route::apiResource('stocks', AdminStockController::class);
        Route::get('stocks/{stock}/buy-details', [AdminStockController::class, 'getBuyDetails']);
        Route::get('stocks/{stock}/details', [AdminStockController::class, 'getBuyDetails']);
        Route::get('stocks/{stock}/pdf', [AdminStockController::class, 'generateBuyDetailsPdf']);
        Route::apiResource('customers', AdminCustomerController::class);
        Route::apiResource('branches', AdminBranchController::class);
        Route::apiResource('branch-prices', AdminBranchPriceController::class);
        Route::apiResource('vehicles', AdminVehicleController::class);
        Route::apiResource('transporters', AdminTransporterController::class);
        Route::apiResource('units', AdminUnitController::class);
        Route::apiResource('alternate-units', AdminAlternateUnitController::class);
        Route::apiResource('dealers', AdminDealerController::class);
        Route::apiResource('purchases', AdminPurchaseController::class);
        Route::post('purchases/{purchase}', [AdminPurchaseController::class, 'update']);
        Route::apiResource('sales', AdminSaleController::class);
        Route::post('sales/{sale}', [AdminSaleController::class, 'update']);
        Route::get('sales/{sale}/pdf', [AdminSaleController::class, 'generatePdf']);

        // Admin Mobile App (mob) Routes
        Route::prefix('mob')->group(function () {
            Route::apiResource('purchases', AdminMobPurchaseController::class);
            Route::post('purchases/{purchase}', [AdminMobPurchaseController::class, 'update']);
            Route::apiResource('sales', AdminMobSaleController::class);
            Route::post('sales/{sale}', [AdminMobSaleController::class, 'update']);
            Route::get('sales/{sale}/pdf', [AdminMobSaleController::class, 'generatePdf']);
            Route::get('gatepasses/{sale}/pdf', [AdminMobSaleController::class, 'generatePdf']);
            Route::get('stocks/{stock}/buy-details', [AdminStockController::class, 'getBuyDetails']);
            Route::get('stocks/{stock}/details', [AdminStockController::class, 'getBuyDetails']);
            Route::get('stocks/{stock}/pdf', [AdminStockController::class, 'generateBuyDetailsPdf']);
        });
    });
});

// User API Routes
Route::prefix('user')->group(function () {
    Route::post('login', [UserAuthController::class, 'login']);
    Route::get('branch-list', [UserBranchController::class, 'branchList']);
    Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
        Route::post('logout', [UserAuthController::class, 'logout']);
        Route::get('profile', [UserAuthController::class, 'profile']);

        Route::apiResource('stocks', UserStockController::class);
        Route::get('stocks/{stock}/buy-details', [UserStockController::class, 'getBuyDetails']);
        Route::get('stocks/{stock}/details', [UserStockController::class, 'getBuyDetails']);
        Route::get('stocks/{stock}/pdf', [UserStockController::class, 'generateBuyDetailsPdf']);

        // Customer User Operations (no update/delete)
        Route::get('customers', [UserCustomerController::class, 'index']);
        Route::post('customers', [UserCustomerController::class, 'store']);
        Route::get('customers/{id}', [UserCustomerController::class, 'show']);

        Route::apiResource('branches', UserBranchController::class);
        Route::apiResource('branch-prices', UserBranchPriceController::class);
        Route::apiResource('vehicles', UserVehicleController::class);
        Route::apiResource('transporters', UserTransporterController::class);
        Route::apiResource('units', UserUnitController::class);
        Route::apiResource('alternate-units', UserAlternateUnitController::class);
        Route::apiResource('dealers', UserDealerController::class);
        Route::apiResource('purchases', UserPurchaseController::class)->except(['update']);
        Route::apiResource('sales', UserSaleController::class)->except(['update']);
        Route::get('gatepasses/{sale}/pdf', [UserSaleController::class, 'generatePdf']);

        // User Mobile App (mob) Routes
        Route::prefix('mob')->group(function () {
            Route::apiResource('purchases', UserMobPurchaseController::class)->except(['update']);
            Route::apiResource('sales', UserMobSaleController::class)->except(['update']);
            Route::get('gatepasses/{sale}/pdf', [UserMobSaleController::class, 'generatePdf']);
            Route::get('stocks/{stock}/buy-details', [UserStockController::class, 'getBuyDetails']);
            Route::get('stocks/{stock}/details', [UserStockController::class, 'getBuyDetails']);
            Route::get('stocks/{stock}/pdf', [UserStockController::class, 'generateBuyDetailsPdf']);
        });
    });
});
