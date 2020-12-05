<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');

Route::get('/', function () {
    return redirect()->route('backend.dashboard');
});
