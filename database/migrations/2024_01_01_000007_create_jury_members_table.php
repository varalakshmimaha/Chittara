<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jury_members', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('designation', 255)->default('');
            $table->string('image', 500)->default('');
            $table->integer('display_order')->default(0);
            $table->tinyInteger('is_active')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jury_members');
    }
};
