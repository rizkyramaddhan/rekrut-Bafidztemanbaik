<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('login.register'); // Menampilkan form registrasi
    }

    public function register(Request $request)
    {
        // Validasi input dari form registrasi
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|string',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                             ->withErrors($validator)
                             ->withInput(); // Kembali dengan error jika validasi gagal
        }

        // Simpan data pengguna baru
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); // Enkripsi password

        // Menyimpan role, status, dan avatar
        $user->role = $request->role;
        $user->status = $request->status;



        // Simpan pengguna ke database
        $user->save();

        return redirect()->route('login')->with('success', 'Registration successful, please login!');
    }

    
}
