<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'title',
        'is_active',
        'video_path'
    ];

    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }
}
