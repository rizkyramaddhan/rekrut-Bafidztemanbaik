<?php

namespace Database\Factories;

use App\Models\Pelamar;
use App\Models\Posisi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PelamarFactory extends Factory
{
    protected $model = Pelamar::class;

    public function definition(): array
    {
        $telpId = '+62' . $this->faker->numerify('8##########');

        return [
            'nama'        => $this->faker->name(),
            'email'       => $this->faker->unique()->safeEmail(),
            'telepon'     => $telpId,
            // relasi FK: kolom = 'posisi' (bukan posisi_id)
            'posisi'      => Posisi::factory()->aktif(),
            'status'      => $this->faker->randomElement(['proses', 'interview', 'training', 'ditolak']),
            // file dummy (silakan sesuaikan path storage publik kamu)
            'cv'          => 'uploads/cv/cv_' . $this->faker->unique()->numberBetween(1, 9999) . '.pdf',
            'ktp'         => 'uploads/ktp/ktp_' . $this->faker->unique()->numberBetween(1, 9999) . '.jpg',
        ];
    }

    // states status proses rekrutmen (opsional)
    public function proses(): self
    {
        return $this->state(fn() => ['status' => 'proses']);
    }

    public function interview(): self
    {
        return $this->state(fn() => ['status' => 'interview']);
    }

    public function training(): self
    {
        return $this->state(fn() => ['status' => 'training']);
    }

    public function ditolak(): self
    {
        return $this->state(fn() => ['status' => 'ditolak']);
    }
}
