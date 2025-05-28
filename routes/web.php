<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', action: function () {
        return Inertia::render('Dashboard');
    });
    Route::resource('role', RoleController::class);
    // Route::get('role', [RoleController::class, 'index'])->name('role.index');
    // Route::get('permission', [PermissionController::class, 'index'])->name('permission.index');
    Route::resource('permission', PermissionController::class);
});
