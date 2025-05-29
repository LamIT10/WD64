<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;




Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    });

    Route::resource('customers', CustomerController::class)->names('customers');
    Route::post('customers/{customer}/ranks', [CustomerController::class, 'storeRank'])->name('customers.ranks.store');


    Route::group(['prefix' => 'suppliers', 'as' => 'suppliers.'], function () {
        Route::get('/', [SupplierController::class, 'getList'])->name('index');
        Route::get('create', [SupplierController::class, 'create'])->name('create');
        Route::get('store', [SupplierController::class, 'store'])->name('store');
    });
});

