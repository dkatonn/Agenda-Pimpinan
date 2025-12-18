<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'tanggal',
        'jam',          
        'tempat',
        'keterangan',
        'disposisi',
        'user_id',
        'profile_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam'     => 'string', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
