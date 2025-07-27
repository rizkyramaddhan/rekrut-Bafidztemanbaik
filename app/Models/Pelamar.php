<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelamar extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'posisi',
        'cv',
        'ktp',
        'kk',
        'pas_foto',
        'ijazah_skck',
        'status',
    ];

    // PERBAIKAN: Eksplisit tentukan foreign key dan local key
public function posisi()
{
    return $this->belongsTo(Posisi::class, 'posisi', 'id');
}
// Accessor
// public function getNamaPosisiAttribute()
// {
//     return $this->posisi ? $this->posisi->nama_posisi : null;
// }
}