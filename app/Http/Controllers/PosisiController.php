<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posisi;

class PosisiController extends Controller
{
    // Menampilkan semua posisi
    public function index()
{
    // Ambil semua posisi dari database
    $posisis = Posisi::all();

    // Menghitung jumlah posisi berdasarkan status
    $totalPosisi = Posisi::count();
    $totalStatusAktif = Posisi::where('status', 'aktif')->count();  // Menghitung posisi yang aktif
    $totalStatusNonAktif = Posisi::where('status', 'non-aktif')->count();  // Menghitung posisi yang non-aktif

    // Merender view dan mengirimkan data posisi ke view
    return view('rekrut.posisi', compact(
        'posisis',
        'totalPosisi',
        'totalStatusAktif',
        'totalStatusNonAktif'
    ));
}


    // Menyimpan posisi baru
    public function store(Request $request)
    {
        // Validasi inputan
        $request->validate([
            'nama_posisi' => 'required|string|max:255',
            'status' => 'required|in:aktif,non-aktif',
        ]);

        // Menyimpan posisi baru
        $posisi = Posisi::create([
            'nama_posisi' => $request->nama_posisi,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Posisi berhasil ditambahkan',
            'data' => $posisi,
        ]);
    }

    // Menampilkan detail posisi berdasarkan ID
    public function show($id)
    {
        $posisi = Posisi::find($id);

        if (!$posisi) {
            return response()->json([
                'success' => false,
                'message' => 'Posisi tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $posisi,
        ]);
    }

    // Mengupdate posisi berdasarkan ID
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_posisi' => 'required|string|max:255',
            'status' => 'required|in:aktif,non-aktif',
        ]);

        $posisi = Posisi::find($id);

        if (!$posisi) {
            return response()->json([
                'success' => false,
                'message' => 'Posisi tidak ditemukan',
            ], 404);
        }

        // Update posisi
        $posisi->update([
            'nama_posisi' => $request->nama_posisi,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Posisi berhasil diperbarui',
            'data' => $posisi,
        ]);
    }

    // Mengubah status posisi (aktif/non-aktif)
    public function toggleStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,non-aktif',
        ]);

        $posisi = Posisi::find($id);

        if (!$posisi) {
            return response()->json([
                'success' => false,
                'message' => 'Posisi tidak ditemukan',
            ], 404);
        }

        // Update status posisi
        $posisi->status = $request->status;
        $posisi->save();

        return response()->json([
            'success' => true,
            'message' => 'Status posisi berhasil diperbarui',
            'data' => $posisi,
        ]);
    }

    // Menghapus posisi
    public function destroy($id)
    {
        $posisi = Posisi::find($id);

        if (!$posisi) {
            return response()->json([
                'success' => false,
                'message' => 'Posisi tidak ditemukan',
            ], 404);
        }

        // Menghapus posisi
        $posisi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Posisi berhasil dihapus',
        ]);
    }

    // Method untuk mengambil data posisi berdasarkan ID
public function edit($id)
{
    $posisi = Posisi::find($id); // Ambil data posisi berdasarkan ID

    if (!$posisi) {
        return response()->json([
            'success' => false,
            'message' => 'Posisi tidak ditemukan',
        ], 404); // Pastikan response JSON dengan status 404 jika posisi tidak ditemukan
    }

    return response()->json([
        'success' => true,
        'data' => $posisi,
    ]);
}


}
