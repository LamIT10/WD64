<?php

use App\Constant\PermissionConstant;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\RankController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;


Route::prefix('admin')->as('admin.')->group(function () {

    Route::get('/dashboard', action: function () {
        return Inertia::render('Dashboard');
    });
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);


    Route::group([
        'prefix' => 'customers',
        'as' => 'customers.'
    ], function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::patch('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('ranks')->as('ranks.')->group(function () {
        Route::get('/', [RankController::class, 'index'])->name('index');        // Danh sách rank của customer
        Route::get('/create', [RankController::class, 'create'])->name('create'); // Form tạo mới
        Route::post('/', [RankController::class, 'store'])->name('store');        // Lưu rank mới
        Route::get('/{rank}', [RankController::class, 'show'])->name('show');     // Chi tiết rank
        Route::get('/{rank}/edit', [RankController::class, 'edit'])->name('edit'); // Form sửa
        Route::patch('/{rank}', [RankController::class, 'update'])->name('update');  // Cập nhật rank
        Route::delete('/{rank}', [RankController::class, 'destroy'])->name('destroy'); // Xóa rank
    });
    
    Route::prefix('permission')->as('permission.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('show');
    });



    // Route::prefix('permission')->as('permission.')->group(function () {

    //     Route::get('/', [PermissionController::class, 'index'])->middleware('has_permission:' . PermissionConstant::PERMISSION_INDEX)->name('index');
    //     Route::get('/create', [PermissionController::class, 'create'])->middleware('has_permission:'. PermissionConstant::PERMISSION_CREATE)->name('create');
    //     Route::post('/', [PermissionController::class, 'store'])->name('store');
    //     Route::get('/{id}/edit', [PermissionController::class, 'edit'])->name('edit');
    //     Route::patch('/{id}', [PermissionController::class, 'update'])->name('update');
    //     Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy');
    //     Route::get('/{id}', [PermissionController::class, 'show'])->name('show');
    // });

    Route::prefix('role')->as('role.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('', [RoleController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');
        Route::post('admin/customers/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('admin.customers.bulk-delete');
        Route::get('admin/customers/import', [CustomerController::class, 'import'])->name('admin.customers.import');
        Route::get('admin/customers/export', [CustomerController::class, 'export'])->name('admin.customers.export');
    });


    Route::group(['prefix' => 'suppliers', 'as' => 'suppliers.'], function () {
        Route::get('/', [SupplierController::class, 'getList'])->name('index');
        Route::get('create', [SupplierController::class, 'create'])->name('create');
        Route::get('{id}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::post('store', [SupplierController::class, 'store'])->name('store');
        Route::patch('{id}/update', [SupplierController::class, 'update'])->name('update');
        Route::delete('{id}', [SupplierController::class, 'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'purchases', 'as' => 'purchases.'], function () {
        Route::get('/', [PurchaseOrderController::class, 'getList'])->name('index');
        Route::get('create', [PurchaseOrderController::class, 'create'])->name('create');
        Route::get('{id}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::post('store', [SupplierController::class, 'store'])->name('store');
        Route::patch('{id}/update', [SupplierController::class, 'update'])->name('update');
        Route::delete('{id}', [SupplierController::class, 'destroy'])->name('destroy');
        Route::get('{id}/get-variants', [PurchaseOrderController::class, 'getVariants'])->name('getVariants');
    });



    Route::group([
        'prefix' => 'users',
        'as' => 'users.',
        'middleware' => ['auth'] // nếu có middleware thì thêm vào đây
    ], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/update-status', action: [UserController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
        Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk-delete');
    });

});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/', function () {
    return Inertia::render('Dashboard');
});
// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
// Route Profile
Route::get('profile', function () {
    return Inertia::render('Auth/Profile');
})->name('profile');
