<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [AttendanceController::class, 'index'])->name('absensi');
Route::get('/face-descriptors', [AttendanceController::class, 'descriptors'])->name('face-descriptors');
Route::post('/face/store', [AttendanceController::class, 'store']);

Auth::routes(); 
Route::middleware(['auth', 'activity'])->group(function () {
    Route::get('/beranda', [HomeController::class, 'index'])->name('beranda');

    // Master Data
    Route::get('/user', [UserController::class, 'index'])->name('md.user');
    Route::post('/user/add', [UserController::class, 'store'])->name('md.user.tambah');
    Route::post('/user/{user}/update', [UserController::class, 'update'])->name('md.user.update');
    Route::delete('/user/{user}/delete', [UserController::class, 'destroy'])->name('md.user.delete');
    Route::get('/user/detail/{id}', [UserController::class, 'show'])->name('md.user.detail');
    Route::post('/user/{user}/face-store', [UserController::class, 'storeFace'])->name('user.face.store');
    Route::post('/user/{user}/face-reset', [UserController::class, 'resetFace'])->name('user.face.reset');

    Route::get('/lokasi', [LokasiController::class, 'index'])->name('md.lokasi');
    Route::post('/lokasi/add', [LokasiController::class, 'store'])->name('md.lokasi.tambah');
    Route::post('/lokasi/{lokasi}/update', [LokasiController::class, 'update'])->name('md.lokasi.update');
    Route::delete('/lokasi/{lokasi}/delete', [LokasiController::class, 'destroy'])->name('md.lokasi.delete');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

});

Route::fallback(function () {
    if (Auth::check()) {
        return redirect('/beranda');
    }
    return redirect('/login');
});
