<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    public $timestamps = false;

    protected $fillable = [
        'site_title',
        'tagline',
        'logo1',
        'logo2',
        'logo3',
        'banner_image',
        'banner_bg',
        'logo_top_left',
        'about_bg',
        'social_twitter',
        'social_instagram',
        'social_youtube',
        'social_facebook',
        'vote_button_text',
        'footer_text',
        'about_text',
    ];
}
