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
        Schema::create('bans', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class, 'banned_by_id')->constrained('users')->cascadeOnDelete();
            $table->string('reason');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bans');
    }
};
