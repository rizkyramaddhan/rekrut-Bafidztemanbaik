<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Menambahkan kolom baru yang dapat diisi secara mass-assignment
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // Menambahkan kolom 'role'
        'status',      // Menambahkan kolom 'status'
        'avatar',      // Menambahkan kolom 'avatar'
    ];

    // Kolom yang akan disembunyikan saat dikonversi ke array atau JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Kolom yang akan di-cast ke tipe data tertentu
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Pastikan password terenkripsi
    ];
}
