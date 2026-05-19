<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AttendanceController::class, 'index'])->name('home');
Route::post('/verify-face', [AttendanceController::class, 'verify']);
Route::post('/register-face', [AttendanceController::class, 'registerFace']); // Opsional untuk daftar
Route::get('/ronaldo-detector', [AttendanceController::class, 'ronaldoDetector'])->name('ronaldo');
