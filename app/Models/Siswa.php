<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;

class Siswa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'siswa';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nisn',
        'nama',
        // 'foto', // Menyimpan URL foto profil siswa
        'kelas_id',
    ];

    // Relasi ke model Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke model Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}