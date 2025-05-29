<?php

use App\Http\Controllers\admin\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', function () {  
        return Inertia::render('Dashboard');
    });

  Route::resource('users', UserController::class);
 
      
});