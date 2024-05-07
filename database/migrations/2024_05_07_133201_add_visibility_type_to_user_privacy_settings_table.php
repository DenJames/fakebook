<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_privacy_settings', function (Blueprint $table) {
            $table->unsignedInteger('visibility_type')->default(1)->after('user_id');
        });
    }
};
