<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelamarController;
use App\Http\Controllers\PosisiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\SettingsController;
use App\Http\Controllers\manajement\AccountController;
use App\Exports\PelamarExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use App\Mail\resetPasswordMail;

// Route yang dapat diakses tanpa login (publik)
// Route untuk halaman Posisi (hanya bisa diakses setelah login)
Route::middleware('auth')->prefix('posisi')->group(function () {
    Route::get('/', [PosisiController::class, 'index'])->name('posisi');
    Route::post('/', [PosisiController::class, 'store'])->name('posisi.store');
    Route::get('{id}', [PosisiController::class, 'show'])->name('posisi.show');
    Route::get('{id}/edit', [PosisiController::class, 'edit'])->name('posisi.edit');  // Hapus '/posisi' agar sesuai dengan prefix

    Route::post('{id}', [PosisiController::class, 'update'])->name('posisi.update');
    Route::patch('{id}/toggle-status', [PosisiController::class, 'toggleStatus'])->name('posisi.toggleStatus');
    Route::delete('{id}', [PosisiController::class, 'destroy'])->name('posisi.delete');
});



// Route untuk login, registrasi, dan pengaturan profil
// Halaman login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

// Proses login
Route::post('login', [LoginController::class, 'login'])->name('login.submit');

// Logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

// Halaman Dashboard (Hanya bisa diakses setelah login)
Route::get('/', function () {
    return view('rekrut.dasbord', [
        'totalPelamar' => \App\Models\Pelamar::count(),
        'totalInterview' => \App\Models\Pelamar::whereDate('created_at', today())->count(),
        'totalPosisi' => \App\Models\Posisi::count(),
        'pelamarPerPosisi' => \App\Models\Posisi::withCount('pelamar')->get(),
        // Menambahkan status berdasarkan proses, interview, dan training
        'statusProses' => \App\Models\Pelamar::where('status', 'proses')->count(),
        'statusInterview' => \App\Models\Pelamar::where('status', 'interview')->count(),
        'totalStatusTraining' => \App\Models\Pelamar::where('status', 'training')->count(),
        'totalStatusTolak' => \App\Models\Pelamar::where('Status', 'ditolak')->count(),
    ]);
})->middleware('auth')->name('home');


// Admin Area (Hanya bisa diakses setelah login)
Route::middleware('auth')->group(function () {
    Route::get('/admin', [PelamarController::class, 'index'])->name('admin.dasbord');
    Route::get('/rekrutmen', [PelamarController::class, 'create'])->name('rekrutmen.form');
    Route::post('/rekrutmen', [PelamarController::class, 'store'])->name('rekrutmen.submit');
    Route::patch('/pelamar/{id}', [PelamarController::class, 'update'])->name('pelamar.update');
    Route::post('/pelamar/multi-delete', [PelamarController::class, 'multiDelete'])->name('pelamar.multiDelete');
    Route::patch('/pelamar/{pelamar}/status', [PelamarController::class, 'updateStatus'])->name('pelamar.updateStatus');
    Route::get('/pelamar/{id}', [PelamarController::class, 'showDetails'])->name('pelamar.show');
    // routes/web.php
// routes/web.php
Route::get('export-excel', [PelamarController::class, 'exportExcel'])->name('export.excel');


});

// Route untuk halaman manajemen akun
Route::prefix('manajement')->middleware('auth')->group(function () {
    Route::get('/manajementUser', [AccountController::class, 'index'])->name('manajement.accounts');
    Route::get('/accounts/{user}', [AccountController::class, 'show'])->name('manajement.accounts.show');
    Route::post('/accounts', [AccountController::class, 'store'])->name('manajement.accounts.store');
    Route::put('/accounts/{user}', [AccountController::class, 'update'])->name('manajement.accounts.update');
    Route::delete('/accounts/{user}', [AccountController::class, 'destroy'])->name('manajement.accounts.destroy');
    Route::patch('/accounts/{user}/toggle-status', [AccountController::class, 'toggleStatus'])->name('manajement.accounts.toggleStatus');
});


Route::get('resetMail' , function () {
    Mail::to('AAA@gmail.com')->send(new resetPasswordMail());
});
