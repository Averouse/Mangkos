<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;

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
