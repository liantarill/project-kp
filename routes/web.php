<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/absensi', [AttendanceController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AttendanceController::class, 'store'])->name('absensi.store');
    Route::get('/riwayat-absensi', [AttendanceController::class, 'history'])->name('absensi.riwayat');

});

require __DIR__.'/auth.php';
