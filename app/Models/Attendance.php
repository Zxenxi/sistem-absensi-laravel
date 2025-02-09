<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'guru_id',   // ID siswa yang melakukan absensi
        'waktu',      // Waktu absensi
        'status',     // Status kehadiran (Hadir, Sakit, Izin, Alfa)
        'lokasi',     // Lokasi GPS absensi
        'foto_wajah', // URL foto wajah saat absensi
    ];

    // Relasi ke model guru
    public function guru(){
    return $this->belongsTo(User::class);
    }
    // Relasi ke model siswa
    public function siswa(){
    return $this->belongsTo(Siswa::class);
    }
    

}