<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nominee extends Model
{
    protected $table = 'nominees';

    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
        'subtitle',
        'image',
        'display_order',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
