<?php

namespace Database\Factories;

use App\Models\Posisi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosisiFactory extends Factory
{
    protected $model = Posisi::class;

    public function definition(): array
    {
        return [
            'nama_posisi' => $this->faker->unique()->jobTitle(), // contoh: "Software Engineer"
            'status'      => $this->faker->randomElement(['aktif', 'nonaktif']),
        ];
    }

    // state siap pakai
    public function aktif(): self
    {
        return $this->state(fn() => ['status' => 'aktif']);
    }

    public function nonaktif(): self
    {
        return $this->state(fn() => ['status' => 'nonaktif']);
    }
}
