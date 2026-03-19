<?php

use App\Http\Controllers\Admin\EpisodeController as AdminEpisodeController;
use App\Http\Controllers\Admin\PartController as AdminPartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserPerkController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Auth;
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

    // Profile & Social
    Route::get('/community', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Friendships
    Route::get('/friend-requests', [FriendshipController::class, 'requests'])->name('friend-requests.index');
    Route::post('/friendships/{user}/send', [FriendshipController::class, 'send'])->name('friendships.send');
    Route::post('/friendships/{user}/accept', [FriendshipController::class, 'accept'])->name('friendships.accept');
    Route::post('/friendships/{user}/reject', [FriendshipController::class, 'reject'])->name('friendships.reject');
    Route::post('/friendships/{user}/unfriend', [FriendshipController::class, 'unfriend'])->name('friendships.unfriend');

    // Chat
    Route::get('/chat/{userId?}', function ($userId = null) {
        return view('chat', ['userId' => $userId]);
    })->name('chat');
    Route::get('/me', fn () => redirect()->route('profile.show', Auth::user()))->name('profile.me');
});

// Public Resource Routes
Route::resource('parts', PartController::class)->only(['index', 'show']);
Route::resource('episodes', EpisodeController::class)->only(['index', 'show']);

// Protected Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', IsAdmin::class])->group(function () {
    Route::resource('parts', AdminPartController::class);
    Route::resource('episodes', AdminEpisodeController::class);
});
