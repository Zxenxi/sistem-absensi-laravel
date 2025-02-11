<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'guru_id',
        'waktu',
        'status',
        'lokasi',
        'foto_wajah',
    ];

    // Relasi ke model guru
    public function guru(){
        return $this->belongsTo(\App\Models\User::class);
    }
    
    // Relasi ke model siswa dengan mapping kolom siswa_id ke user_id pada tabel siswa
    public function siswa(){
        return $this->belongsTo(\App\Models\User::class, 'siswa_id');
    }
    
    
}