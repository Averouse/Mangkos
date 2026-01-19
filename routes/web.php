<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\KostController;
use App\Http\Controllers\MatchmakingController;
use App\Http\Controllers\NotificationController;

// Landing page
Route::get('/', function () {
    return view('pages.landing');
})->name('landing');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Kost search route
Route::get('/kost-search', [KostController::class, 'kostSearch'])->name('kost.search');
Route::get('/kost/{id}', [KostController::class, 'show'])->name('kost.detail');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'owner') {
            return redirect()->route('owner.dashboard');
        }
        return view('user.dashboard');
    })->name('dashboard');
    
    // Owner routes - simple approach
    Route::get('/owner/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
    Route::post('/owner/kosts', [OwnerController::class, 'store'])->name('owner.kosts.store');
    Route::post('/owner/ktp-upload', [OwnerController::class, 'uploadKtp'])->name('owner.ktp.upload');
    // Add this inside the protected routes section
    Route::post('/user/ktm-upload', [AuthController::class, 'uploadKtm'])->name('user.ktm.upload');
    Route::post('/user/profile/update', [AuthController::class, 'updateProfile'])->name('user.profile.update');
    Route::post('/user/profile/photo', [AuthController::class, 'uploadProfilePhoto'])->name('user.profile.photo');
    Route::post('/owner/profile/update', [OwnerController::class, 'updateProfile'])->name('owner.profile.update');
    Route::post('/owner/profile/photo', [OwnerController::class, 'uploadProfilePhoto'])->name('owner.profile.photo');
    Route::post('/owner/kosts/{id}/toggle-full', [OwnerController::class, 'toggleFull'])->name('owner.kosts.toggle-full');
    Route::post('/owner/kosts/{id}/update', [OwnerController::class, 'update'])->name('owner.kosts.update');
    Route::post('/owner/rental/{id}/approve', [OwnerController::class, 'approveRental'])->name('owner.rental.approve');
    Route::post('/owner/rental/{id}/reject', [OwnerController::class, 'rejectRental'])->name('owner.rental.reject');
    
    // Matchmaking routes
    Route::get('/matchmaking', [MatchmakingController::class, 'index'])->name('matchmaking.index');
    Route::get('/matchmaking/kost/{kostId}', [MatchmakingController::class, 'selectKost'])->name('matchmaking.select');
    Route::post('/matchmaking/profile', [MatchmakingController::class, 'saveProfile'])->name('matchmaking.save');
    Route::get('/matchmaking/results/{kostId}', [MatchmakingController::class, 'results'])->name('matchmaking.results');
    Route::post('/matchmaking/toggle-visibility', [MatchmakingController::class, 'toggleVisibility'])->name('matchmaking.toggle-visibility');
    
    // Rental Application routes
    Route::post('/rental/apply', [KostController::class, 'applyRental'])->name('rental.apply');
    
    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/users/{id}/approve', [AdminController::class, 'approveUser'])->name('admin.users.approve');
        Route::post('/users/{id}/reject', [AdminController::class, 'rejectUser'])->name('admin.users.reject');
        Route::post('/kosts/{id}/approve', [AdminController::class, 'approveKost'])->name('admin.kosts.approve');
        Route::post('/kosts/{id}/reject', [AdminController::class, 'rejectKost'])->name('admin.kosts.reject');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});
