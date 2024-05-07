<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_privacy_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();

            $table->boolean('allow_friend_requests')->default(true);
            $table->boolean('show_biography')->default(true);
            $table->boolean('show_join_date')->default(true);
            $table->boolean('show_friend_list')->default(true);
            $table->boolean('show_photo_list')->default(true);

            $table->timestamps();
        });

        foreach (User::all() as $user) {
            $user->privacySettings()->create();
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_privacy_settings');
    }
};
