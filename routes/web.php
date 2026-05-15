<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/verify-face', [AttendanceController::class, 'verify']);
Route::post('/register-face', [AttendanceController::class, 'registerFace']); // Opsional untuk daftar