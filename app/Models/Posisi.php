<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Posisi extends Model
{
    use HasFactory;
    protected $table = 'posisis';


    protected $fillable = [
        'nama_posisi',
        'status',
    ];


    // PERBAIKAN: Eksplisit tentukan foreign key dan local key
    public function pelamar()
    {
        // hasMany(RelatedModel, foreignKey, localKey)
        return $this->hasMany(Pelamar::class, 'posisi', 'id');
    }
}
