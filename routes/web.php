<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    });
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
});
