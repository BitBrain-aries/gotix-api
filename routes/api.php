<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\TransactionController; // ✅ DITAMBAHKAN
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\FilmTerfavoriteController;
use App\Http\Controllers\Api\Admin\GrafikBookingController;
use App\Http\Controllers\Api\Admin\KursiTerfavoriteController;
use App\Http\Controllers\Api\Admin\MakananTerfavoriteController;
use App\Http\Controllers\Api\Admin\PenjualanController;
use App\Http\Middleware\IsAdmin;

// --- PUBLIC ROUTES ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- PROTECTED ROUTES (USER & ADMIN) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profile User
    Route::get('/profile', [UserController::class, 'show']);
    Route::post('/profile', [UserController::class, 'store']);

    // Katalogue Film
    Route::get('/movies', [MovieController::class, 'index']);

    // Checkout
    Route::post('/checkout', [CheckoutController::class, 'process']);

    // ✅ DIPERBAIKI: riwayat & detail transaksi dipindah ke TransactionController
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->whereNumber('id');

    // --- ROUTE ADMIN ---
    Route::middleware(IsAdmin::class)->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index']);
        Route::get('/reports/top-movies', [FilmTerfavoriteController::class, 'index']);
        Route::get('/reports/booking-charts', [GrafikBookingController::class, 'index']);
        Route::get('/reports/top-seats', [KursiTerfavoriteController::class, 'index']);
        Route::get('/reports/top-foods', [MakananTerfavoriteController::class, 'index']);
        Route::get('/sales', [PenjualanController::class, 'index']);
    });
});