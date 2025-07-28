<?php
namespace App\Exports;

use App\Models\Pelamar;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup; // Pastikan PageSetup diimpor dari worksheet
use Maatwebsite\Excel\Events\AfterSheet;

class PelamarExport implements FromArray, WithHeadings, WithStyles, WithEvents, ShouldAutoSize, WithColumnFormatting
{
    protected $posisiList;

    public function __construct($posisiList)
    {
        $this->posisiList = $posisiList;
    }

    public function array(): array
    {
        $pelamars = \App\Models\Pelamar::all();

        $data = [];
        $nomor = 1;
        foreach ($pelamars as $pelamar) {
            $posisiNama = $this->posisiList[$pelamar->posisi] ?? 'Posisi Tidak Diketahui';
            
            // Format nomor telepon agar sesuai dengan format yang diinginkan (misal: (021) 1234-5678)
            $formattedTelepon = $this->formatPhoneNumber($pelamar->telepon);

            $data[] = [
                $nomor++,
                $pelamar->nama,
                $pelamar->email,
                $posisiNama,
                ucfirst($pelamar->status),
                $pelamar->created_at->format('d M Y'),
                $formattedTelepon, // Menambahkan kolom nomor telepon yang sudah diformat
            ];
        }

        return $data;
    }

    // Fungsi untuk memformat nomor telepon
    private function formatPhoneNumber($phone)
    {
        // Menghapus semua karakter non-numeric
        $phone = preg_replace('/\D/', '', $phone);

        // Memastikan nomor telepon memiliki panjang yang sesuai
        if (strlen($phone) === 10) {
            return $phone; // Mengembalikan nomor telepon hanya sebagai angka
        }

        return $phone; // Jika tidak sesuai, kembalikan nomor telepon seperti aslinya
    }

    public function headings(): array
    {
        return [
            ['LAPORAN DATA PELAMAR REKRUTMEN'],
            ['No', 'Nama', 'Email', 'Posisi', 'Status', 'Tanggal Apply', 'Nomor Telepon'], // Menambahkan judul untuk kolom nomor telepon
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Judul besar
        $sheet->mergeCells('A1:G1'); // Memperluas merge cell untuk kolom tambahan
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'name' => 'Calibri'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Header utama
        $sheet->getStyle('A2:G2')->applyFromArray([ // Memperbarui untuk kolom tambahan
            'font' => ['bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9EAD3']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], // Align header ke tengah
        ]);

        // Align semua data ke tengah
        $sheet->getStyle('A3:G' . $sheet->getHighestRow())->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Menyesuaikan lebar kolom secara otomatis
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Mengatur page setup untuk satu halaman dan margin
        $pageSetup = $sheet->getPageSetup();
        $pageSetup->setOrientation(PageSetup::ORIENTATION_PORTRAIT); // Atur ke portrait
        $pageSetup->setFitToWidth(1); // Memastikan konten masuk dalam satu halaman
        $pageSetup->setFitToHeight(1); // Memastikan konten masuk dalam satu halaman

        // Mengatur margin menggunakan setMargins
        $sheet->getPageMargins()->setTop(0.5);    // Margin atas
        $sheet->getPageMargins()->setBottom(0.5); // Margin bawah
        $sheet->getPageMargins()->setLeft(0.5);   // Margin kiri
        $sheet->getPageMargins()->setRight(0.5);  // Margin kanan
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Border dari header sampai akhir data
                $sheet->getStyle("A2:G$lastRow")->applyFromArray([ // Memperbarui untuk kolom tambahan
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    'font' => ['name' => 'Calibri', 'size' => 11],
                ]);

                // Set format nomor telepon sebagai number, nonaktifkan separator ribuan
                $sheet->getStyle('G3:G' . $lastRow)->getNumberFormat()->setFormatCode('0'); // Format nomor telepon tanpa pemisah ribuan

                // Wrap teks
                foreach (range('A', 'G') as $col) { // Memperbarui untuk kolom tambahan
                    $sheet->getStyle($col . '2:' . $col . $lastRow)
                          ->getAlignment()->setWrapText(true);
                }
            },
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => 'DD MMM YYYY',
        ];
    }
}
