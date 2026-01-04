<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\MedicineCategoryController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesTransactionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.signIn');

    Route::prefix('/password')->name('password.')->group(function () {
        Route::get('/send-mail', [AuthController::class, 'sendMailView'])->name('sendMail');
        Route::post('/send', [AuthController::class, 'sendMailProcess'])->name('send');

        Route::get('/reset-password/{token}', [AuthController::class, 'forgotPasswordView'])->name('resetPassword');
        Route::post('/reset', [AuthController::class, 'forgotPasswordProcess'])->name('reset');
    });
});

Route::get('/audit-logs/export', [ActivityLogController::class, 'export'])->name('audit-logs.export');

Route::prefix('/admin')->middleware('auth')->name('admin.')->group(function () {

    Route::get('/', [BaseController::class, 'index'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('/medicine')->name('medicine')->group(function(){
        Route::get('/', [MedicineController::class, 'index']);
        Route::post('/', [MedicineController::class, 'store']);
        Route::post('/show', [MedicineController::class, 'show']);
        Route::put('/{medicine:medicine_id}', [MedicineController::class, 'update']);
        Route::delete('/{medicine:medicine_id}', [MedicineController::class, 'destroy']);
    });

    Route::prefix('/medicine-category')->name('medicine-category')->group(function(){
        Route::get('/', [MedicineCategoryController::class, 'index']);
        Route::post('/', [MedicineCategoryController::class, 'store']);
        Route::post('/show', [MedicineCategoryController::class, 'show']);
        Route::put('/{medicineCategory:category_id}', [MedicineCategoryController::class, 'update']);
        Route::delete('/{medicineCategory:category_id}', [MedicineCategoryController::class, 'destroy']);
    });

    Route::prefix('/medicine-order')->name('medicine-order')->group(function(){
        Route::get('/', [MedicineOrderController::class, 'index']);
        Route::post('/', [MedicineOrderController::class, 'store']);
        Route::post('/show', [MedicineOrderController::class, 'show']);
        Route::put('/{medicineOrder:order_id}', [MedicineOrderController::class, 'update']);
        Route::delete('/{medicineOrder:order_id}', [MedicineOrderController::class, 'destroy']);
    });

    Route::prefix('/activity-log')->name('activity-log')->group(function(){
        Route::get('/', [ActivityLogController::class, 'index']);
        Route::post('/', [ActivityLogController::class, 'store']);
        Route::post('/show', [ActivityLogController::class, 'show']);
        Route::put('/{activityLog:id}', [ActivityLogController::class, 'update']);
        Route::delete('/{activityLog:id}', [ActivityLogController::class, 'destroy']);
    });

    Route::prefix('/suppliers')->name('suppliers-data')->group(function(){
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::post('/show', [SupplierController::class, 'show']);
        Route::put('/{supplier:supplier_id}', [SupplierController::class, 'update']);
        Route::delete('/{supplier:supplier_id}', [SupplierController::class, 'destroy']);
    });

    Route::prefix('/users')->name('users-data.')->group(function(){
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::post('/show', [UserController::class, 'show'])->name('show');
        
        Route::put('/{user:user_id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user:user_id}', [UserController::class, 'destroy'])->name('destroy');
    }); 

    Route::prefix('/reports')->name('reports.')->group(function(){
        Route::get('/medicine-report', [ReportController::class, 'medicineReport'])->name('medicine-report');
        Route::get('/medicine-report-export', [ReportController::class, 'medicineReportExport'])->name('medicine-report-export');

        Route::get('/operational-report', [ReportController::class, 'operationalReport'])->name('operational-report');
        Route::get('/operational-report-export', [ReportController::class, 'operationalReportExport'])->name('operational-report-export');

        Route::get('/financial-report', [ReportController::class, 'financialReport'])->name('financial-report');
        Route::get('/financial-report-export', [ReportController::class, 'financialReportExport'])->name('financial-report-export');
    });

    Route::prefix('/cashier-menu')->name('cashier-menu')->group(function(){
        Route::get('/', [SalesTransactionController::class, 'index']);
        Route::post('/', [SalesTransactionController::class, 'store'])->name('.transaction.store');
    });

});