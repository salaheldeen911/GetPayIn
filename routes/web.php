<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\PlatformController;
use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
})->name('welcome');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/login', 'auth.login')->name('login');
});

// Auth routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Posts
    Route::resource('posts', PostController::class);
    Route::post('posts/{post}/schedule', [PostController::class, 'schedule'])->name('posts.schedule');

    // Platforms
    Route::get('/platforms', [PlatformController::class, 'index'])->name('platforms.index');
    Route::post('/platforms/{platform}/connect', [PlatformController::class, 'connect'])->name('platforms.connect');
    Route::delete('/platforms/{platform}/disconnect', [PlatformController::class, 'disconnect'])->name('platforms.disconnect');
    Route::put('/platforms/{platform}/toggle-active', [PlatformController::class, 'toggleActive'])->name('platforms.toggle-active');
});
