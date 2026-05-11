<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email_change_pending')->nullable()->after('password_change_locked_until');
            $table->string('email_change_otp_hash', 64)->nullable()->after('email_change_pending');
            $table->timestamp('email_change_otp_expires_at')->nullable()->after('email_change_otp_hash');
            $table->unsignedTinyInteger('email_change_verify_failed_count')->default(0)->after('email_change_otp_expires_at');
            $table->timestamp('email_change_locked_until')->nullable()->after('email_change_verify_failed_count');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_change_pending',
                'email_change_otp_hash',
                'email_change_otp_expires_at',
                'email_change_verify_failed_count',
                'email_change_locked_until',
            ]);
        });
    }
};
