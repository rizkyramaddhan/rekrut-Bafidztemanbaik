<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Pastikan model User diimpor
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    // Menampilkan halaman edit profil
    public function edit()
    {
        // Mengambil data pengguna yang sedang login
        $user = Auth::user(); // Mendapatkan informasi pengguna yang sedang login
        return view('login.profile', compact('user')); // Kirim data pengguna ke view
    }

    // Menangani pembaruan profil pengguna
    public function update(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(), // Pastikan email unik kecuali milik pengguna saat ini
        ]);

        // Jika validasi gagal, kembali ke form dengan error
        if ($validator->fails()) {
            return redirect()->route('profile.edit')
                             ->withErrors($validator)
                             ->withInput(); // Kembali dengan error jika validasi gagal
        }

        // Ambil instansi model User yang sedang login
        $user = Auth::user(); // Pastikan ini adalah model User yang benar

        // Cek jika objek $user valid
        if (!$user instanceof User) {
            return redirect()->route('profile.edit')->with('error', 'User not found!');
        }

        // Update informasi profil pengguna
        $user->name = $request->name;
        $user->email = $request->email;

        // Jika ada avatar baru, simpan avatar baru
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public'); // Menyimpan avatar
            $user->avatar = $avatarPath;
        }

        // Simpan perubahan pada model User
        $user->save(); // Pastikan $user adalah objek dari model User

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }
}
