<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');
Route::get('/product', 'ProductController@index')->name('product.index');
Route::get('/', function () {
    return redirect()->route('backend.dashboard');
});
