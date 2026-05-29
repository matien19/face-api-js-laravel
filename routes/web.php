<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [AttendanceController::class, 'index'])->name('absensi');
Route::get('/face-descriptors', [AttendanceController::class, 'descriptors'])->name('face-descriptors');

Auth::routes();
Route::middleware('auth')->group(function () {
    Route::get('/beranda', [HomeController::class, 'index'])->name('beranda');

    // Master Data
    Route::get('/user', [UserController::class, 'index'])->name('md.user');
    Route::post('/user/add', [UserController::class, 'store'])->name('md.user.tambah');
    Route::get('/user/detail/{id}', [UserController::class, 'show'])->name('md.user.detail');

});

Route::fallback(function () {
    if (Auth::check()) {
        return redirect('/beranda');
    }
    return redirect('/login');
});
