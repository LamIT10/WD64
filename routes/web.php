<?php

use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', function () {  
        return Inertia::render('Dashboard');
    });
    Route::group(['prefix' => 'suppliers', 'as' => 'suppliers.'], function () {
        Route::get('/', [SupplierController::class, 'getList'])->name('index');
        Route::get('create', [SupplierController::class, 'create'])->name('create');
        Route::get('store', [SupplierController::class, 'store'])->name('store');
    });

  Route::resource('users', UserController::class);
 
      
});