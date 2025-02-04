<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guru extends Model
{
    use HasFactory;
    
    protected $table = 'guru';

    protected $fillable = [
        'nama',
        // 'foto',
        'user_id', // Tambahkan ini agar user_id dapat diisi
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}