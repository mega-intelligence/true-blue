<?php

use App\Http\Controllers\Backend\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::get('/', function () {
    return redirect()->route('backend.dashboard');
});
