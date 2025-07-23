<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BasketController;

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

    // Public Basket Routes (no authentication required)
    Route::post('/basket/confirm', [BasketController::class, 'confirm'])->name('api.basket.confirm');
    Route::get('/basket/{id}', [BasketController::class, 'show'])->name('api.basket.show');
    Route::post('/basket/{id}/pay', [BasketController::class, 'simulatePayment']);

    // Protected Routes (Sanctum + custom middleware)
    Route::middleware(['auth:sanctum', 'auth.token'])->group(function () {
        // Authenticated user routes
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'apiLogout'])->name('api.logout');
            Route::get('/user', [AuthController::class, 'user'])->name('api.user');
        });

        // 🛒 Cart Routes
        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('api.cart.index');
            Route::post('/', [CartController::class, 'add'])->name('api.cart.add');
            Route::put('/{productId}', [CartController::class, 'update'])->name('api.cart.update');
            Route::delete('/{productId}', [CartController::class, 'remove'])->name('api.cart.remove');
            Route::delete('/', [CartController::class, 'clear'])->name('api.cart.clear');
            Route::post('/checkout', [CartController::class, 'checkout'])->name('api.cart.checkout');
        });
    });

    // Fallback (404 handler)
    Route::fallback(function () {
        return response()->json(['message' => 'Endpoint not found'], 404);
    });
});
