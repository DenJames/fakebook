<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('friendships', function (Blueprint $table): void {
            $table->id();

            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('friend_id');

            $table->timestamp('accepted_at')->nullable();

            $table->timestamps();

            $table->foreign('friend_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('friendships');
    }
};
