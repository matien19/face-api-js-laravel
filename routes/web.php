<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [AttendanceController::class, 'index'])->name('absensi');
Route::post('/verify-face', [AttendanceController::class, 'verify']);
Route::post('/register-face', [AttendanceController::class, 'registerFace']); // Opsional untuk daftar
Route::get('/ronaldo-detector', [AttendanceController::class, 'ronaldoDetector'])->name('ronaldo');

Auth::routes();
Route::middleware('auth')->group(function () {
    Route::get('/beranda', [HomeController::class, 'index'])->name('beranda');
    Route::get('/user', [UserController::class, 'index'])->name('md.user');
});
