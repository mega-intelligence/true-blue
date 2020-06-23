<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');
Route::get('/product', 'ProductController@index')->name('product.index');
Route::get('/warehouse', 'WarehouseController@index')->name('warehouse.index');
Route::get('/warehouse/create', 'WarehouseController@create')->name('warehouse.create');

Route::get('/', function () {
    return redirect()->route('backend.dashboard');
});
