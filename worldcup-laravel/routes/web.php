<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLoginController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [MatchController::class, 'index'])->name('home');
Route::get('/match/{id}', [MatchController::class, 'show'])->name('match.show');

/*
|--------------------------------------------------------------------------
| Admin Login
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AdminLoginController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Panel (protected by AdminAuth middleware)
|--------------------------------------------------------------------------
*/

Route::middleware(\App\Http\Middleware\AdminAuth::class)->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('index');

    // Match CRUD
    Route::get('/matches/create', [AdminController::class, 'create'])->name('matches.create');
    Route::post('/matches', [AdminController::class, 'store'])->name('matches.store');
    Route::get('/matches/{id}', [AdminController::class, 'showMatch'])->name('matches.show');
    Route::get('/matches/{id}/edit', [AdminController::class, 'edit'])->name('matches.edit');
    Route::put('/matches/{id}', [AdminController::class, 'update'])->name('matches.update');
    Route::delete('/matches/{id}', [AdminController::class, 'destroy'])->name('matches.destroy');

    // Video management
    Route::post('/matches/{matchId}/videos', [AdminController::class, 'storeVideo'])->name('videos.store');
    Route::delete('/matches/{matchId}/videos/{videoId}', [AdminController::class, 'destroyVideo'])->name('videos.destroy');
});
