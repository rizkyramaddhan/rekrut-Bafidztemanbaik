<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelamar;
use App\Models\Posisi;

class PelamarSeeder extends Seeder
{
    public function run(): void
    {
        // Buat daftar posisi fix (status aktif)
        $daftar = [
            'Software Engineer',
            'UI/UX Designer',
            'Data Analyst',
            'HR Generalist',
            'Digital Marketer',
        ];

        foreach ($daftar as $nama) {
            $posisi = Posisi::firstOrCreate(
                ['nama_posisi' => $nama],
                ['status' => 'aktif']
            );

            // 8 pelamar random + 1 tiap state untuk contoh
            Pelamar::factory()->count(8)->create(['posisi' => $posisi->id]);

            Pelamar::factory()->proses()->create(['posisi' => $posisi->id]);
            Pelamar::factory()->interview()->create(['posisi' => $posisi->id]);
            Pelamar::factory()->training()->create(['posisi' => $posisi->id]);
            Pelamar::factory()->ditolak()->create(['posisi' => $posisi->id]);
        }

        // Tambahan: 10 pelamar dengan Posisi acak otomatis (akan bikin posisi baru aktif)
        // Pelamar::factory()->count(10)->create();
    }
}
