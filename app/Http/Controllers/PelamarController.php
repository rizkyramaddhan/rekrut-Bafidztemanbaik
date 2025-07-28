<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Pelamar;
use App\Models\Posisi;
use App\Exports\PelamarExport;
use Maatwebsite\Excel\Facades\Excel;

class PelamarController extends Controller
{
    public function posisi()
    {
        $posisis = Posisi::all();
        return view('rekrut/posisi', compact('posisis'));
    }

    public function index()
    {
        $pelamars = Pelamar::paginate(10);
        $posisiList = Posisi::pluck('nama_posisi', 'id')->toArray();
        $posisis = Posisi::where('status', 'aktif')->get();

        return view('rekrut.index', [
            'pelamars'            => $pelamars,
            'posisiList'          => $posisiList,
            'totalPelamar'        => Pelamar::count(),
            'totalInterview'      => Pelamar::whereDate('created_at', today())->count(),
            'totalPosisi'         => Posisi::count(),
            'pelamarPerPosisi'    => Posisi::withCount('pelamar')->get(),
            'statusProses'        => Pelamar::where('status', 'proses')->count(),
            'statusInterview'     => Pelamar::where('status', 'interview')->count(),
            'totalStatusTraining' => Pelamar::where('status', 'training')->count(),
            'totalStatusTolak'    => Pelamar::where('status', 'ditolak')->count(),
            'posisis' => $posisis
        ]);
    }

    public function create()
    {
        $posisis = Posisi::where('status', 'aktif')->get();
        return view('rekrut.rekrut', compact('posisis'));
    }

    public function showDetails($id)
    {
        $pelamar = Pelamar::findOrFail($id);
        $posisiList = Posisi::pluck('nama_posisi', 'id')->toArray();
        return view('rekrut.detail', compact('pelamar', 'posisiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'email'   => 'required|email:rfc,dns|max:100',
            'telepon' => 'required|regex:/^[0-9]{11,13}$/',
            'posisi'  => 'required|exists:posisis,id',
            'cv'      => 'required|file|mimes:pdf|max:5120',
            'ktp'     => 'required|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        $cvPath  = $request->file('cv')->store('lamaran/cv', 'public');
        $ktpPath = $request->file('ktp')->store('lamaran/ktp', 'public');

        Pelamar::create([
            'nama'    => $validated['nama'],
            'email'   => $validated['email'],
            'telepon' => $validated['telepon'],
            'posisi'  => $validated['posisi'],
            'cv'      => $cvPath,
            'ktp'     => $ktpPath,
        ]);

        return redirect()->route('rekrutmen.form')->with('success', 'Lamaran berhasil dikirim!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:proses,interview,training,ditolak,diterima',
        ]);

        $pelamar = Pelamar::findOrFail($id);

        if (in_array($pelamar->status, ['diterima', 'ditolak'])) {
            return response()->json(['success' => false, 'message' => 'Status sudah tidak bisa diubah.']);
        }

        $pelamar->status = $request->input('status');
        $pelamar->save();

        return response()->json(['success' => true, 'message' => 'Status pelamar berhasil diubah.']);
    }

    public function update(Request $request, $id)
{
    $pelamar = Pelamar::findOrFail($id);

    // Validation rules for the incoming request
    $validated = $request->validate([
        'nama'   => 'required|string|max:255',
        'posisi' => 'required|exists:posisis,id',  // Assuming `posisi` is the ID of a position from the `Posisi` model
        'status' => 'required|string|in:proses,interview,training,ditolak,diterima', // Add all valid statuses
        'cv'     => 'nullable|file|mimes:pdf|max:10240', // Max file size of 10MB
        'ktp'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max file size of 10MB
    ]);

    // Prepare the data to update
    $data = [
        'nama'   => $request->nama,
        'posisi' => $request->posisi,
        'status' => $request->status,
    ];

    // Handle CV file upload
    if ($request->hasFile('cv')) {
        // Store the file and get the path
        $cvPath = $request->file('cv')->store('cv', 'public'); // Store the CV file in the 'cv' directory within the 'public' disk
        $data['cv'] = $cvPath; // Save the path to the database
    }

    // Handle KTP file upload
    if ($request->hasFile('ktp')) {
        // Store the file and get the path
        $ktpPath = $request->file('ktp')->store('ktp', 'public'); // Store the KTP file in the 'ktp' directory within the 'public' disk
        $data['ktp'] = $ktpPath; // Save the path to the database
    }

    // Update the pelamar record with the new data
    $pelamar->update($data);

    // Return a response
    return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
}


    public function multiDelete(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'exists:pelamars,id',
        ]);

        $pelamars = Pelamar::whereIn('id', $request->ids)->get();
        $deletedCount = 0;

        foreach ($pelamars as $pelamar) {
            // Hapus file dari storage jika ada
            if ($pelamar->cv && Storage::disk('public')->exists($pelamar->cv)) {
                Storage::disk('public')->delete($pelamar->cv);
            }
            if ($pelamar->ktp && Storage::disk('public')->exists($pelamar->ktp)) {
                Storage::disk('public')->delete($pelamar->ktp);
            }

            $pelamar->delete();
            $deletedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} pelamar berhasil dihapus dan file terkait dibersihkan.",
        ]);
    }

    public function exportExcel()
    {
        $posisiList = Posisi::pluck('nama_posisi', 'id')->toArray();
        return Excel::download(new PelamarExport($posisiList), 'pelamar.xlsx');
    }
}
