<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->string('voter_name', 255);
            $table->string('voter_mobile', 15);
            $table->string('voter_location', 255);
            $table->string('ip_address', 50);
            $table->timestamp('voted_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
