<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinancialController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::group(['prefix' => 'app', 'middleware' => 'check.auth'], function () {

    // Financial Routes
    Route::prefix('financial')->group(function () {
        Route::get('/', [FinancialController::class, 'index'])->name('app.financial.index');
        Route::get('/statistics', [FinancialController::class, 'statistics'])->name('app.financial.statistics');
        Route::get('/{record_id}', [FinancialController::class, 'detail'])->name('app.financial.detail');
    });
});

// Redirect root ke halaman keuangan
Route::get('/', function () {
    return redirect()->route('app.financial.index');
});
