<?php

<<<<<<< HEAD
use App\Http\Controllers\ProductController;
<<<<<<< HEAD
use App\Http\Controllers\SaleOrderController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('layouts.main');
=======
=======

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\Admin\Authorization\PermissionController ;
use App\Http\Controllers\Admin\Authorization\RoleController;
>>>>>>> 102007491a1acb411fb449cb9e89c1487e68ea78
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Controller;


Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', action: function () {
        return Inertia::render('Dashboard');
    });
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    Route::resource('customers', CustomerController::class)->names('customers');
    Route::post('customers/{customer}/ranks', [CustomerController::class, 'storeRank'])->name('customers.ranks.store');


    
    Route::prefix('permission')->as('permission.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('show');
    });
    
    Route::prefix('role')->as('role.')->group(function () {
        Route::get('/', [RoleController::class,'index'])->name('index');
        Route::get('/create', [RoleController::class,'create'])->name('create');
        Route::post('', [RoleController::class,'store'])->name('store');
        Route::get('/{id}/edit', [RoleController::class,'edit'])->name('edit');
        Route::patch('/{id}', [RoleController::class,'update'])->name('update');
        Route::delete('/{id}', [RoleController::class,'destroy'])->name('destroy');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');

    });

    
    Route::group(['prefix' => 'suppliers', 'as' => 'suppliers.'], function () {
        Route::get('/', [SupplierController::class, 'getList'])->name('index');
        Route::get('create', [SupplierController::class, 'create'])->name('create');
        Route::get('store', [SupplierController::class, 'store'])->name('store');
    });
<<<<<<< HEAD
>>>>>>> 6fd5b45cfc3bb5aa0a21d0b9cbf22a0c21640e96
});
Route::get('sale-order/export/', [SaleOrderController::class, 'export'])->name('order.export');
=======
    
    
    Route::resource('users', UserController::class);
    
});
      
>>>>>>> 102007491a1acb411fb449cb9e89c1487e68ea78
