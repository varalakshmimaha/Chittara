<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'gallery';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'image',
        'display_order',
        'is_active',
    ];
}
