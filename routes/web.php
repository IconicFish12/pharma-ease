<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\MedicineCategoryController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesTransactionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/admin')->group(function () {

    Route::get('/', [BaseController::class, 'index']);

    Route::prefix('/medicine')->group(function(){
        Route::get('/', [MedicineController::class, 'index']);
        Route::post('/', [MedicineController::class, 'store']);
        Route::post('/show', [MedicineController::class, 'show']);
        Route::put('/{medicine:id}', [MedicineController::class, 'update']);
        Route::delete('/{medicine:id}', [MedicineController::class, 'destroy']);
    })->name('medicine');


    Route::prefix('/medicine-category')->group(function(){
        Route::get('/', [MedicineCategoryController::class, 'index']);
        Route::post('/', [MedicineCategoryController::class, 'store']);
        Route::post('/show', [MedicineCategoryController::class, 'show']);
        Route::put('/{medicineCategory:id}', [MedicineCategoryController::class, 'update']);
        Route::delete('/{medicineCategory:id}', [MedicineCategoryController::class, 'destroy']);
    })->name('medicine-category');

    Route::prefix('/medicine-order')->group(function(){
        Route::get('/', [MedicineOrderController::class, 'index']);
        Route::post('/', [MedicineOrderController::class, 'store']);
        Route::post('/show', [MedicineOrderController::class, 'show']);
        Route::put('/{medicineOrder:id}', [MedicineOrderController::class, 'update']);
        Route::delete('/{medicineOrder:id}', [MedicineOrderController::class, 'destroy']);
    })->name('medicine-order');

    Route::prefix('/activity-log')->group(function(){
        Route::get('/', [ActivityLogController::class, 'index']);
        Route::post('/', [ActivityLogController::class, 'store']);
        Route::post('/show', [ActivityLogController::class, 'show']);
        Route::put('/{activityLog:id}', [ActivityLogController::class, 'update']);
        Route::delete('/{activityLog:id}', [ActivityLogController::class, 'destroy']);
    })->name('activity-log');

    Route::prefix('/suppliers')->group(function(){
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::post('/show', [SupplierController::class, 'show']);
        Route::put('/{supplier:id}', [SupplierController::class, 'update']);
        Route::delete('/{supplier:id}', [SupplierController::class, 'destroy']);
    })->name('suppliers-data');

    Route::prefix('/users')->group(function(){
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::post('/show', [UserController::class, 'show']);
        Route::put('/{user:id}', [UserController::class, 'update']);
        Route::delete('/{user:id}', [UserController::class, 'destroy']);
    })->name('users-data');

    Route::prefix('/reports')->group(function(){
        Route::get('/', [ReportController::class, 'index']);
    })->name('pharmacy-report');

    Route::prefix('/cashier-menu')->group(function(){
        Route::get('/', [SalesTransactionController::class, 'index']);
        Route::post('/', [SalesTransactionController::class, 'store']);
        Route::post('/show', [SalesTransactionController::class, 'show']);
        Route::put('/{medicine:id}', [SalesTransactionController::class, 'update']);
        Route::delete('/{medicine:id}', [SalesTransactionController::class, 'destroy']);
    })->name('cashier-menu');

})->name('admin');
