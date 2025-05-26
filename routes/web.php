<?php


use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', function () {  
        return Inertia::render('Dashboard');
    });
});
