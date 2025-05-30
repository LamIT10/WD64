<?php

use App\Http\Controllers\ProductController;
<<<<<<< HEAD
use App\Http\Controllers\SaleOrderController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('layouts.main');
=======
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    });
    Route::group(['prefix' => 'suppliers', 'as' => 'suppliers.'], function () {
        Route::get('/', [SupplierController::class, 'getList'])->name('index');
        Route::get('create', [SupplierController::class, 'create'])->name('create');
        Route::get('store', [SupplierController::class, 'store'])->name('store');
    });
>>>>>>> 6fd5b45cfc3bb5aa0a21d0b9cbf22a0c21640e96
});
Route::get('sale-order/export/', [SaleOrderController::class, 'export'])->name('order.export');
