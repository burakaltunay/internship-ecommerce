<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public Auth Routes (API)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'apiRegister'])->name('api.register');
        Route::post('/login', [AuthController::class, 'apiLogin'])->name('api.login');

        // CSRF endpoint (for SPA using Sanctum)
        Route::get('/csrf-cookie', function () {
            return response()->json(['message' => 'CSRF cookie set']);
        })->name('api.csrf');
    });

    // Public Product Routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('api.products.index');
        Route::get('/categories', [ProductController::class, 'categories'])->name('api.products.categories');
    });

    // Protected Routes (Sanctum + custom middleware)
    Route::middleware(['auth:sanctum', 'auth.token'])->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'apiLogout'])->name('api.logout');
            Route::get('/user', [AuthController::class, 'user'])->name('api.user');
        });
    });

    // Fallback (404 handler)
    Route::fallback(function () {
        return response()->json(['message' => 'Endpoint not found'], 404);
    });
});
