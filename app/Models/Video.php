<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'youtube_url',
        'display_order',
        'is_active',
    ];
}
