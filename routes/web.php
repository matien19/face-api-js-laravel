<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [AttendanceController::class, 'index'])->name('absensi');
Route::post('/verify-face', [AttendanceController::class, 'verify']);
Route::post('/register-face', [AttendanceController::class, 'registerFace']); // Opsional untuk daftar
Route::get('/ronaldo-detector', [AttendanceController::class, 'ronaldoDetector'])->name('ronaldo');

Auth::routes();

Route::get('/beranda', [HomeController::class, 'index'])->name('home');
