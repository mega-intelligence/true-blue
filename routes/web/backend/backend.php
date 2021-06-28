<?php

use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::get('/category', [CategoryController::class, 'index'])->name('category.index');

Route::get('/products', [ProductController::class, 'index'])->name('product.index');

Route::get('/', function () {
    return redirect()->route('backend.dashboard');
});
