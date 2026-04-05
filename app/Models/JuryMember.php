<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JuryMember extends Model
{
    protected $table = 'jury_members';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'designation',
        'image',
        'display_order',
        'is_active',
    ];
}
