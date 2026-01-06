<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':participant'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/absensi', [AttendanceController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AttendanceController::class, 'store'])->name('absensi.store');
    Route::get('/download', [AttendanceController::class, 'export'])->name('absensi.export');

    Route::get('/riwayat-absensi', [AttendanceController::class, 'history'])->name('absensi.riwayat');
    Route::get('/riwayat-absensi/photos', [AttendanceController::class, 'photos'])->name('absensi.photos');
    Route::get('/riwayat-absensi/{attendance}', [AttendanceController::class, 'detail'])->name('absensi.detail');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

});

require __DIR__.'/auth.php';
