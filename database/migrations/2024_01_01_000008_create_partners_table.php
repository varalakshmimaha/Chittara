<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('image', 500)->default('');
            $table->string('website', 500)->default('');
            $table->integer('display_order')->default(0);
            $table->tinyInteger('is_active')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
