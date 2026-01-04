<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineCategoryController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineOrderController;
use App\Http\Controllers\SalesTransactionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'loginProcess'])->middleware('guest');

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('/admin')->group(function () {

    Route::prefix('/medicine')->group(function(){
        Route::get('/', [MedicineController::class, 'index']);
        Route::post('/', [MedicineController::class, 'store']);
        Route::post('/show', [MedicineController::class, 'show']);
        Route::put('/{medicine:medicine_id}', [MedicineController::class, 'update']);
        Route::delete('/{medicine:medicine_id}', [MedicineController::class, 'destroy']);
    });


    Route::prefix('/medicine-category')->group(function(){
        Route::get('/', [MedicineCategoryController::class, 'index']);
        Route::post('/', [MedicineCategoryController::class, 'store']);
        Route::post('/show', [MedicineCategoryController::class, 'show']);
        Route::put('/{medicineCategory:category_id}', [MedicineCategoryController::class, 'update']);
        Route::delete('/{medicineCategory:category_id}', [MedicineCategoryController::class, 'destroy']);
    });

    Route::prefix('/medicine-order')->group(function(){
        Route::get('/', [MedicineOrderController::class, 'index']);
        Route::post('/', [MedicineOrderController::class, 'store']);
        Route::post('/show', [MedicineOrderController::class, 'show']);
        Route::put('/{medicineOrder:order_id}', [MedicineOrderController::class, 'update']);
        Route::delete('/{medicineOrder:order_id}', [MedicineOrderController::class, 'destroy']);
    });

    Route::prefix('/activity-log')->group(function(){
        Route::get('/', [ActivityLogController::class, 'index']);
    });

    Route::prefix('/suppliers')->group(function(){
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::post('/show', [SupplierController::class, 'show']);
        Route::put('/{supplier:supplier_id}', [SupplierController::class, 'update']);
        Route::delete('/{supplier:supplier_id}', [SupplierController::class, 'destroy']);
    });

    Route::prefix('/users')->group(function(){
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::post('/show', [UserController::class, 'show']);
        Route::put('/{user:user_id}', [UserController::class, 'update']);
        Route::delete('/{user:user-id}', [UserController::class, 'destroy']);
    });

    Route::prefix('/cashier-menu')->group(function(){
        Route::get('/', [SalesTransactionController::class, 'index']);
        Route::post('/', [SalesTransactionController::class, 'store']);
    });

});
