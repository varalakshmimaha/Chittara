<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vote_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vote_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('nominee_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('nominee_id')->references('id')->on('nominees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vote_details');
    }
};
