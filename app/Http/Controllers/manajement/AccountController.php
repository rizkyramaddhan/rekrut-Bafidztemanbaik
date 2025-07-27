<?php

namespace App\Http\Controllers\manajement;

use App\Http\Controllers\Controller;
use App\Models\User; // Menggunakan model User
use Illuminate\Http\Request;

class AccountController extends Controller
{
    // Menampilkan daftar akun dengan pagination
    public function index()
{
    // Mengambil data pengguna dengan pagination
    $users = User::paginate(10); // Anda dapat menyesuaikan jumlah pagination

    // Menghitung total akun, akun aktif, dan akun non-aktif
    $totalAccount = User::count(); // Total akun
    $statusAktif = User::where('status', 'aktif')->count(); // Total akun aktif
    $statusNonAktif = User::where('status', 'nonaktif')->count(); // Total akun non-aktif

    // Mengembalikan data ke view manajementUser.blade.php
    return view('manajement.manajementUser', compact('users', 'totalAccount', 'statusAktif', 'statusNonAktif'));
}


    // Menampilkan detail akun berdasarkan ID
    public function show($id)
    {
        // Menampilkan detail pengguna berdasarkan ID
        $user = User::findOrFail($id);
        return response()->json(['success' => true, 'data' => $user]);
    }

    // Menyimpan akun baru
    public function store(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email', // Pastikan email unik
        'role' => 'required|string',
        'status' => 'required|string',
        'password' => 'required|string|min:6|confirmed', // Pastikan password valid
    ]);

    // Hash password sebelum menyimpannya
    $validated['password'] = bcrypt($request->password);

    // Membuat akun baru
    $user = User::create($validated); // Membuat user baru di database

    return response()->json(['success' => true, 'data' => $user], 201);
}


 public function update(Request $request, $id)
{
    // Validasi input
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id, // Memastikan email unik kecuali untuk akun yang sedang diedit
        'role' => 'required|string',
        'status' => 'required|string',
        'password' => 'nullable|string|min:6|confirmed', // Jika password diisi, harus valid
    ]);

    // Mencari user berdasarkan ID
    $user = User::findOrFail($id);

    // Jika password diisi, hash password baru
    if ($request->has('password') && !empty($request->password)) {
        $validated['password'] = bcrypt($request->password);
    } else {
        unset($validated['password']); // Hapus password dari validasi jika tidak diisi
    }

    // Mengupdate akun
    $user->update($validated);

    return response()->json(['success' => true, 'data' => $user]);
}

// App/Http/Controllers/UserController.php

public function destroy($id)
{
    try {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil dihapus.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menghapus akun.'
        ]);
    }
}


// Fungsi untuk mengubah status akun (aktif/nonaktif)
public function toggleStatus($id)
{
    // Cari user berdasarkan ID
    $user = User::findOrFail($id);

    // Toggle status akun
    $newStatus = $user->status == 'aktif' ? 'nonaktif' : 'aktif';
    $user->status = $newStatus;

    // Simpan perubahan status
    $user->save();

    // Mengembalikan response dengan status terbaru
    return response()->json([
        'success' => true,
        'message' => 'Status akun berhasil diubah',
        'newStatus' => $newStatus  // Pastikan server mengirimkan status terbaru
    ]);
}

}
