<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/users', [AuthController::class, 'getAllUsers']);

// Endpoint Hapus Akun Khusus Mobile Flutter
Route::delete('/profile/delete', [AuthController::class, 'deleteSelf']);