<?php


use App\Http\Controllers\CustomerController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\Admin\Authorization\PermissionController;
use App\Http\Controllers\Admin\Authorization\RoleController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SaleOrderController;

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
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('', [RoleController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');
    });


    Route::group(['prefix' => 'suppliers', 'as' => 'suppliers.'], function () {
        Route::get('/', [SupplierController::class, 'getList'])->name('index');
        Route::get('create', [SupplierController::class, 'create'])->name('create');
        Route::get('store', [SupplierController::class, 'store'])->name('store');
    });


    Route::resource('users', UserController::class);
    Route::prefix('sale-orders')->as('sale-orders.')->group(function () {
        Route::get('/', [SaleOrderController::class, 'index'])->name('index');
        Route::post('/', [SaleOrderController::class, 'create'])->name('create');
    });
});
