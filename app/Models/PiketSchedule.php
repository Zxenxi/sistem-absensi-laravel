<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
class PiketSchedule extends Model
{
    use HasFactory;
    use HasRoles;

    protected $fillable = ['guru_id', 'schedule_date', 'start_time', 'end_time'];

    public function guru() {
        return $this->belongsTo(User::class, 'guru_id');
    }
    
}