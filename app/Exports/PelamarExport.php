<?php

namespace App\Exports;

use App\Models\Pelamar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PelamarExport implements FromCollection, WithHeadings
{
    protected $posisiList;

    public function __construct($posisiList)
    {
        // Menyimpan posisiList yang diberikan dari controller
        $this->posisiList = $posisiList;
    }

    /**
     * Fungsi untuk mendapatkan data yang akan diekspor.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil semua pelamar dengan relasi posisi
        $pelamars = Pelamar::all(); // Menggunakan get() untuk mendapatkan koleksi Eloquent

        return $pelamars->map(function ($pelamar) {
            // Mengambil nama posisi berdasarkan ID
            $posisiNama = $this->posisiList[$pelamar->posisi] ?? 'Posisi Tidak Diketahui'; // Menggunakan posisiList yang sudah di-passing

            return [
                $pelamar->id,
                $pelamar->nama,
                $pelamar->email,
                $posisiNama, // Mengambil nama posisi berdasarkan posisiList
                $pelamar->status,
                $pelamar->created_at->format('d M Y'), // Format tanggal apply
            ];
        });
    }

    /**
     * Menentukan heading kolom pada file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID', 'Nama', 'Email', 'Posisi', 'Status', 'Tanggal Apply',
        ];
    }
}
