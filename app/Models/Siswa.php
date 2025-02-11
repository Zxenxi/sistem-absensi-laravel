<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;

class Siswa extends Model
{
//     use HasFactory;

//     protected $table = 'siswa';
//     protected $primaryKey = 'id';

//     protected $fillable = [
//         'nisn',
//         'nama',
//         'kelas_id',
//         'user_id', // Tambahkan ini agar user_id dapat diisi secara mass assignment
//     ];

//     public function kelas()
//     {
//         return $this->belongsTo(Kelas::class);
//     }

//     public function attendances()
//     {
//         return $this->hasMany(Attendance::class);
//     }
//     public function user()
// {
//     return $this->belongsTo(\App\Models\User::class);
// }
}