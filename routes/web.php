<?php

<<<<<<< HEAD
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
=======
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
>>>>>>> fece6d1f3b00cb86a1b2ff82df705779601203c3
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    });
<<<<<<< HEAD
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
=======
    Route::group(['prefix' => 'suppliers', 'as' => 'suppliers.'], function () {
        Route::get('/', [SupplierController::class, 'getList'])->name('index');
        Route::get('create', [SupplierController::class, 'create'])->name('create');
        Route::get('store', [SupplierController::class, 'store'])->name('store');
    });
>>>>>>> fece6d1f3b00cb86a1b2ff82df705779601203c3
});
