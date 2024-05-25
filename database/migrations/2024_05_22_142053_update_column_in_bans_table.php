<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bans', function (Blueprint $table): void {
            // make banned_by_id nullable
            $table->unsignedBigInteger('banned_by_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bans', function (Blueprint $table): void {
            // make banned_by_id not nullable
            $table->unsignedBigInteger('banned_by_id')->change();
        });
    }
};
