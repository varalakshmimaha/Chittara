<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavLink extends Model
{
    protected $table = 'nav_links';

    public $timestamps = false;

    protected $fillable = [
        'label',
        'url',
        'display_order',
        'is_active',
    ];
}
