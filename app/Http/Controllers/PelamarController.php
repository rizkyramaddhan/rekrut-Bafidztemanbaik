<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Pelamar;
use App\Models\Posisi;
use App\Exports\PelamarExport;
use Maatwebsite\Excel\Facades\Excel;

class PelamarController extends Controller
{
    public function posisi()
    {
        // Ambil semua posisi dari tabel 'posisis'
        $posisis = Posisi::all();
        return view('rekrut/posisi', compact('posisis'));
    }

// Tambahkan ini di controller index untuk debug

public function index()
{
    $pelamars = Pelamar::paginate(10);
    $posisiList = \App\Models\Posisi::pluck('nama_posisi', 'id')->toArray();

    $totalPelamar = Pelamar::count();
    $totalInterview = Pelamar::whereDate('created_at', today())->count();
    $totalPosisi = Posisi::count();
    $pelamarPerPosisi = Posisi::withCount('pelamar')->get();

    // Menambahkan query untuk total status berdasarkan proses, interview, dan training
    $statusProses = Pelamar::where('status', 'proses')->count();
    $statusInterview = Pelamar::where('status', 'interview')->count();
    $totalStatusTraining = Pelamar::where('status', 'training')->count();
    $totalStatusTolak = Pelamar::where('status', 'ditolak')->count(); // Menambahkan status tolak

    return view('rekrut.index', compact(
        'pelamars',
        'totalPelamar',
        'totalInterview',
        'totalPosisi',
        'pelamarPerPosisi',
        'posisiList',
        'statusProses',        // Mengirimkan total status "proses"
        'statusInterview',     // Mengirimkan total status "interview"
        'totalStatusTraining',  // Mengirimkan total status "training"
        'totalStatusTolak' // Mengirimkan total status tolak
    ));
}



    public function create()
    {
        // Ambil posisi dengan status aktif
    $posisis = Posisi::where('status', 'aktif')->get(); // Ambil hanya posisi yang aktif

    return view('rekrut.rekrut', compact('posisis'));
    }

   public function showDetails($id)
{
    $pelamar = Pelamar::findOrFail($id);
    
    // Jika file PDF, pastikan di-set header untuk tampilan preview
    // $cvPath = storage_path('app/public/' . $pelamar->cv);
    // if (pathinfo($cvPath, PATHINFO_EXTENSION) == 'pdf') {
    //     return response()->file($cvPath, [
    //         'Content-Type' => 'application/pdf',
    //         'Content-Disposition' => 'inline; filename="' . basename($cvPath) . '"'
    //     ]);
    // }

    return view('rekrut/detail', compact('pelamar'));
}


    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|max:100',
            'telepon' => 'required|regex:/^[0-9]{11,13}$/',
            'posisi' => 'required|exists:posisis,id', // Validasi ID posisi

            // Validasi untuk file CV
            'cv' => 'required|file|mimes:pdf|max:5120',  // Maksimum 5MB

            // Validasi untuk file KTP
            'ktp' => 'required|image|mimes:jpg,jpeg,png|max:1024', // Maksimum 1MB
        ]);

        // Simpan file CV ke storage
        $cvPath = $request->file('cv')->store('lamaran/cv', 'public');

        // Simpan file KTP ke storage
        $ktpPath = $request->file('ktp')->store('lamaran/ktp', 'public');

        // Menyimpan pelamar dan relasinya dengan posisi
        Pelamar::create([
            'nama'       => $validated['nama'],
            'email'      => $validated['email'],
            'telepon'    => $validated['telepon'],
            'posisi'     => $validated['posisi'], // Menyimpan ID posisi
            'cv'         => $cvPath,
            'ktp'        => $ktpPath,
        ]);

        return redirect()->route('rekrutmen.form')->with('success', 'Lamaran berhasil dikirim!');
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi status yang diterima
        $request->validate([
            'status' => 'required|in:proses,interview,training,ditolak,diterima',
        ]);

        // Temukan pelamar berdasarkan ID
        $pelamar = Pelamar::findOrFail($id);

        // Pastikan status tidak dapat diubah jika sudah diterima atau ditolak
        if ($pelamar->status == 'diterima' || $pelamar->status == 'ditolak') {
            return response()->json(['success' => false, 'message' => 'Status sudah tidak bisa diubah.']);
        }

        // Update status pelamar
        $pelamar->status = $request->input('status');
        $pelamar->save();

        return response()->json(['success' => true, 'message' => 'Status pelamar berhasil diubah.']);
    }

    public function update(Request $request, $id)
{
    $pelamar = Pelamar::findOrFail($id);
    $pelamar->nama = $request->nama;
    $pelamar->posisi = $request->posisi;
    $pelamar->status = $request->status;
    $pelamar->save();

    return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
}

public function multiDelete(Request $request)
{
    // Validate the incoming request to ensure it's an array of IDs
    $request->validate([
        'ids' => 'required|array|min:1',
        'ids.*' => 'exists:pelamars,id'
    ]);

    // Delete the selected pelamars
    $deletedCount = Pelamar::destroy($request->ids);

    return response()->json([
        'success' => true,
        'message' => "{$deletedCount} pelamar berhasil dihapus"
    ]);
}

public function exportExcel()
{
    // Ambil daftar posisi untuk diteruskan ke ekspor
    $posisiList = \App\Models\Posisi::pluck('nama_posisi', 'id')->toArray();

    // Menggunakan PelamarExport dan mengirimkan posisiList
    return \Maatwebsite\Excel\Facades\Excel::download(new PelamarExport($posisiList), 'pelamar.xlsx');
}


}
