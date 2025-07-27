<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posisi extends Model
{
    protected $table = 'posisis';

    protected $fillable = [
        'nama_posisi', 
        'departemen', 
        'deskripsi', 
        'requirements', 
        'benefits', 
        'salary_range', 
        'status',
    ];

    // PERBAIKAN: Eksplisit tentukan foreign key dan local key
    public function pelamar()
    {
        // hasMany(RelatedModel, foreignKey, localKey)
        return $this->hasMany(Pelamar::class, 'posisi', 'id');
    }
}