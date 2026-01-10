<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('siswa', \App\Http\Controllers\Admin\SiswaController::class);

    // Tindak Lanjut (Admin Access)
    Route::get('/tindak-lanjut', [\App\Http\Controllers\TindakLanjutController::class, 'index'])->name('tindak_lanjut.index');
    Route::get('/tindak-lanjut/{poin}/proses', [\App\Http\Controllers\TindakLanjutController::class, 'create'])->name('tindak_lanjut.create');
    Route::post('/tindak-lanjut/{poin}', [\App\Http\Controllers\TindakLanjutController::class, 'store'])->name('tindak_lanjut.store');
});

// Guru Routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');

    // Guru can also view Siswa List but read-only (using same controller logic or specific)
    Route::get('/siswa', [\App\Http\Controllers\Admin\SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/{siswa}', [\App\Http\Controllers\Admin\SiswaController::class, 'show'])->name('siswa.show');

    // Tindak Lanjut (Guru Access)
    Route::get('/tindak-lanjut', [\App\Http\Controllers\TindakLanjutController::class, 'index'])->name('tindak_lanjut.index');
    Route::get('/tindak-lanjut/{poin}/proses', [\App\Http\Controllers\TindakLanjutController::class, 'create'])->name('tindak_lanjut.create');
    Route::post('/tindak-lanjut/{poin}', [\App\Http\Controllers\TindakLanjutController::class, 'store'])->name('tindak_lanjut.store');
});

// Shared Routes for Input Poin (accessible by auth users, logic checks role inside or middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/input-poin', [\App\Http\Controllers\PoinController::class, 'create'])->name('poin.create');
    Route::post('/input-poin', [\App\Http\Controllers\PoinController::class, 'store'])->name('poin.store');
});

// Siswa Routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');
});
