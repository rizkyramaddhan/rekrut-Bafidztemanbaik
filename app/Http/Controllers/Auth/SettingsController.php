<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    // Menampilkan halaman pengaturan akun (misalnya mengganti password, role, status, avatar)
    public function edit()
    {
        // Mendapatkan data pengguna yang sedang login
        $user = Auth::user(); // Mendapatkan informasi pengguna yang sedang login
        return view('login.setting', compact('user')); // Mengirimkan data pengguna ke view
    }

    // Menangani pembaruan pengaturan akun
    public function update(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'password' => 'nullable|min:6|confirmed', // Validasi password jika ada perubahan
            
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings')
                             ->withErrors($validator)
                             ->withInput(); // Kembali dengan error jika validasi gagal
        }

        // Ambil instansi model User yang sedang login
        $user = Auth::user(); // Pastikan ini adalah model User yang benar

        // Cek jika objek $user valid
        if (!$user instanceof \App\Models\User) {
            return redirect()->route('settings')->with('error', 'User not found!');
        }

        // Update password pengguna jika ada perubahan
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password); // Enkripsi password baru
        }

        // // Update role dan status
        // $user->role = $request->role;
        // $user->status = $request->status;

        // // Jika ada avatar baru, simpan avatar baru
        // if ($request->hasFile('avatar')) {
        //     // Menyimpan avatar
        //     $avatarPath = $request->file('avatar')->store('avatars', 'public'); // Menyimpan avatar ke folder public/avatars
        //     $user->avatar = $avatarPath;
        // }

        // Simpan perubahan pada model User
        $user->save(); // Pastikan $user adalah objek dari model User

        return redirect()->route('settings')->with('success', 'Settings updated successfully.');
    }
}
