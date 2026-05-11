<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('password_change_failed_count')->default(0)->after('avatar');
            $table->timestamp('password_change_locked_until')->nullable()->after('password_change_failed_count');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['password_change_failed_count', 'password_change_locked_until']);
        });
    }
};
