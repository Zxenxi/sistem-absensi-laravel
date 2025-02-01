<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Siswa;

class Kelas extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kelas';

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
    use HasFactory;

    protected $fillable = [
        'kelas',
        'jurusan',
        'tahun_ajaran',
    ];

    // Relasi ke model Siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
}