<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $table = 'partners';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'image',
        'website',
        'display_order',
        'is_active',
    ];
}
