<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('login.login');  // pastikan nama view sesuai dengan yang ada di folder resources/views
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input dari form login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Mengecek kredensial pengguna menggunakan Auth
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // Jika login berhasil, redirect ke halaman dashboard atau halaman yang diminta sebelumnya
            return redirect()->intended('dashboard');
        }

        // Jika login gagal, beri pesan error
        return back()
            ->withErrors(['email' => 'Email atau password yang Anda masukkan salah.'])
            ->withInput();  // Menyertakan data input yang telah dimasukkan agar email tetap ada di form
    }

    // Menangani logout
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
