<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $table = 'votes';

    public $timestamps = false;

    protected $fillable = [
        'voter_name',
        'voter_mobile',
        'voter_location',
        'ip_address',
    ];

    public function voteDetails()
    {
        return $this->hasMany(VoteDetail::class);
    }
}
