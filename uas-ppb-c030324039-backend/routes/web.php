<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ROUTE HALAMAN PROFIL WEB BARU
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

    // CRUD ADMIN WEB
    Route::get('/users/{id}/edit', [AuthController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AuthController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AuthController::class, 'deleteUser'])->name('users.destroy');

    // HAPUS AKUN MANDIRI WEB
    Route::delete('/profile/delete', [AuthController::class, 'deleteSelf'])->name('profile.destroy');
});