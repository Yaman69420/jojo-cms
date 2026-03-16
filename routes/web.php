<?php

use App\Http\Controllers\Admin\EpisodeController as AdminEpisodeController;
use App\Http\Controllers\Admin\PartController as AdminPartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPerkController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User Perks
    Route::post('/episodes/{episode}/rate', [UserPerkController::class, 'rate'])->name('episodes.rate');
    Route::delete('/episodes/{episode}/rate', [UserPerkController::class, 'deleteRating'])->name('episodes.rate.delete');
    Route::post('/favorites/{type}/{id}', [UserPerkController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::post('/episodes/{episode}/watched', [UserPerkController::class, 'toggleWatched'])->name('episodes.toggle-watched');

    // Profile / Sanctuary
    Route::get('/sanctuary', [ProfileController::class, 'show'])->name('profile.show');
});

// Public Resource Routes
Route::resource('parts', PartController::class)->only(['index', 'show']);
Route::resource('episodes', EpisodeController::class)->only(['index', 'show']);

// Protected Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', IsAdmin::class])->group(function () {
    Route::resource('parts', AdminPartController::class);
    Route::resource('episodes', AdminEpisodeController::class);
});
