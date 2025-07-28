<?php

<<<<<<< HEAD
use App\Exports\PelamarExport;
use App\Mail\resetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosisiController;
use App\Http\Controllers\PelamarController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SettingsController;
use App\Http\Controllers\manajement\AccountController;
<<<<<<< HEAD
=======
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
>>>>>>> a4d1d5e8ba46a0a90d34e6a0ae3d62c6a686f004
=======
use App\Http\Middleware\RoleMiddleware; // Tambahan penting
>>>>>>> v1.1

// ✅ Route untuk ADMIN & USER
Route::middleware(['auth', RoleMiddleware::class . ':admin,user'])->group(function () {

    // DASHBOARD
    Route::get('/', function () {
        return view('rekrut.dasbord', [
            'totalPelamar' => \App\Models\Pelamar::count(),
            'totalInterview' => \App\Models\Pelamar::whereDate('created_at', today())->count(),
            'totalPosisi' => \App\Models\Posisi::count(),
            'pelamarPerPosisi' => \App\Models\Posisi::withCount('pelamar')->get(),
            'statusProses' => \App\Models\Pelamar::where('status', 'proses')->count(),
            'statusInterview' => \App\Models\Pelamar::where('status', 'interview')->count(),
            'totalStatusTraining' => \App\Models\Pelamar::where('status', 'training')->count(),
            'totalStatusTolak' => \App\Models\Pelamar::where('Status', 'ditolak')->count(),
        ]);
    })->name('home');

    // POSISI
    Route::prefix('posisi')->group(function () {
        Route::get('/', [PosisiController::class, 'index'])->name('posisi');
        Route::post('/', [PosisiController::class, 'store'])->name('posisi.store');
        Route::get('{id}', [PosisiController::class, 'show'])->name('posisi.show');
        Route::get('{id}/edit', [PosisiController::class, 'edit'])->name('posisi.edit');
        Route::post('{id}', [PosisiController::class, 'update'])->name('posisi.update');
        Route::patch('{id}/toggle-status', [PosisiController::class, 'toggleStatus'])->name('posisi.toggleStatus');
        Route::delete('{id}', [PosisiController::class, 'destroy'])->name('posisi.delete');
    });

    // PELAMAR
    Route::get('/admin', [PelamarController::class, 'index'])->name('admin.dasbord');
    Route::get('/rekrutmen', [PelamarController::class, 'create'])->name('rekrutmen.form');
    Route::post('/rekrutmen', [PelamarController::class, 'store'])->name('rekrutmen.submit');
    Route::patch('/pelamar/{id}', [PelamarController::class, 'update'])->name('pelamar.update');
    Route::post('/pelamar/multi-delete', [PelamarController::class, 'multiDelete'])->name('pelamar.multiDelete');
    Route::patch('/pelamar/{pelamar}/status', [PelamarController::class, 'updateStatus'])->name('pelamar.updateStatus');
    Route::get('/pelamar/{id}', [PelamarController::class, 'showDetails'])->name('pelamar.show');
    Route::get('export-excel', [PelamarController::class, 'exportExcel'])->name('export.excel');
});

// ✅ Route khusus untuk ADMIN SAJA
Route::prefix('manajement')->middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/manajementUser', [AccountController::class, 'index'])->name('manajement.accounts');
    Route::get('/accounts/{user}', [AccountController::class, 'show'])->name('manajement.accounts.show');
    Route::post('/accounts', [AccountController::class, 'store'])->name('manajement.accounts.store');
    Route::put('/accounts/{user}', [AccountController::class, 'update'])->name('manajement.accounts.update');
    Route::delete('/accounts/{user}', [AccountController::class, 'destroy'])->name('manajement.accounts.destroy');
    Route::patch('/accounts/{user}/toggle-status', [AccountController::class, 'toggleStatus'])->name('manajement.accounts.toggleStatus');
});

// ✅ Autentikasi & Profil
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
<<<<<<< HEAD
<<<<<<< HEAD
// Route untuk halaman lupa password
=======

// ✅ Reset Password
>>>>>>> v1.1
Route::get('password/reset', [PasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [PasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [PasswordController::class, 'reset'])->name('password.update');
<<<<<<< HEAD
=======

>>>>>>> a4d1d5e8ba46a0a90d34e6a0ae3d62c6a686f004
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


<<<<<<< HEAD
// Route::get('resetMail' , function () {
//     Mail::to('AAA@gmail.com')->send(new resetPasswordMail());
// });
=======
Route::get('resetMail' , function () {
    Mail::to('AAA@gmail.com')->send(new resetPasswordMail());
});
>>>>>>> a4d1d5e8ba46a0a90d34e6a0ae3d62c6a686f004
=======
>>>>>>> v1.1
