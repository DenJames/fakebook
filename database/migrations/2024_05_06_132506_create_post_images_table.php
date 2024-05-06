<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_images', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Post::class)->constrained('posts')->cascadeOnDelete();
            $table->string('path');
            $table->string('file_hash')->nullable();
            $table->string('name');
            $table->string('caption')->nullable();
            $table->integer('views')->default(0);
            $table->integer('size');
            $table->integer('width');
            $table->integer('height');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_images');
    }
};
