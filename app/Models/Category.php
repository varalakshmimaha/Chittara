<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public $timestamps = false;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'display_order',
        'is_active',
    ];

    public function nominees()
    {
        return $this->hasMany(Nominee::class);
    }
}
