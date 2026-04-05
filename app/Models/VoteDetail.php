<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteDetail extends Model
{
    protected $table = 'vote_details';

    public $timestamps = false;

    protected $fillable = [
        'vote_id',
        'category_id',
        'nominee_id',
    ];

    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function nominee()
    {
        return $this->belongsTo(Nominee::class);
    }
}
