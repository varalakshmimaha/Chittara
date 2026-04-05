<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title', 255)->default('Chittara Star Awards 2026');
            $table->string('tagline', 255)->default('Most Awaited Sandalwood Awards Show');
            $table->string('logo1', 500)->default('');
            $table->string('logo2', 500)->default('');
            $table->string('logo3', 500)->default('');
            $table->string('banner_image', 500)->default('');
            $table->string('banner_bg', 500)->default('');
            $table->string('logo_top_left', 500)->default('');
            $table->string('about_bg', 500)->default('');
            $table->string('social_twitter', 255)->default('');
            $table->string('social_instagram', 255)->default('');
            $table->string('social_youtube', 255)->default('');
            $table->string('social_facebook', 255)->default('');
            $table->string('vote_button_text', 255)->default('Click here To Vote Now');
            $table->text('footer_text')->nullable();
            $table->text('about_text')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
