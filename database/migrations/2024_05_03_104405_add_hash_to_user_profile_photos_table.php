<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('user_profile_photos', function (Blueprint $table): void {
            $table->string('file_hash')->after('path')->unique()->nullable();
        });
    }
};
