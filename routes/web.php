<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Ana sayfa - Public
Route::get('/', function () {
    return view('welcome'); // veya ana sayfa view'ınız
})->name('home');

// Auth Routes - Public (Form-based)
// Direct routes (Laravel standartları)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'webLogin'])->name('web.login');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'webRegister'])->name('web.register');

// AJAX Routes (Frontend JavaScript için)
Route::post('/ajax/login', [AuthController::class, 'login'])->name('web.ajax.login');
Route::post('/ajax/register', [AuthController::class, 'register'])->name('web.ajax.register');

// Web register endpoint (alternatif)
Route::post('/web-register', [AuthController::class, 'register'])->name('web.register.ajax');

// Alternatif prefix'li rotalar (eski uyumluluk için)
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('web.login.form');
    Route::post('/login', [AuthController::class, 'webLogin'])->name('auth.login');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('web.register.form');
    Route::post('/register', [AuthController::class, 'webRegister'])->name('auth.register');
});

// Protected Routes - Authenticated Users Only
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard'); // Dashboard view'ınız
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'webLogout'])->name('web.logout');

    // Diğer korumalı web rotaları buraya eklenebilir
    // Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    // Route::get('/orders', [OrderController::class, 'index'])->name('orders');
});

// Guest Routes - Only for non-authenticated users
Route::middleware(['guest'])->group(function () {
    // Giriş yapmamış kullanıcılar için özel rotalar
    // Örneğin: özel landing page, promo sayfalar vs.
});

// Fallback Route - 404 handler
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
