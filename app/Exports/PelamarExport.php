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

            $data[] = [
                $nomor++,
                $pelamar->nama,
                $pelamar->email,
                $posisiNama,
                ucfirst($pelamar->status),
                $pelamar->created_at->format('d M Y'),
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            ['LAPORAN DATA PELAMAR REKRUTMEN'],
            ['No', 'Nama', 'Email', 'Posisi', 'Status', 'Tanggal Apply'],
            ['Nomor Urut', 'Nama Lengkap', 'Alamat Email', 'Jabatan Dilamar', 'Tahapan Proses', 'Tanggal Mendaftar'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Judul besar
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'name' => 'Calibri'],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Header utama
        $sheet->getStyle('A2:F2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9EAD3']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Sub judul
        $sheet->getStyle('A3:F3')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '555555']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Border dari header sampai akhir data
                $sheet->getStyle("A2:F$lastRow")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    'font' => ['name' => 'Calibri', 'size' => 11],
                ]);

                // Wrap teks
                foreach (range('A', 'F') as $col) {
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
