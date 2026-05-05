<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('client_mail_status', 20)->nullable()->after('is_read');
            $table->string('admin_mail_status', 20)->nullable()->after('client_mail_status');
            $table->text('client_mail_reason')->nullable()->after('admin_mail_status');
            $table->text('admin_mail_reason')->nullable()->after('client_mail_reason');
            $table->string('reply_method', 20)->nullable()->after('admin_mail_reason');
            $table->text('reply_message')->nullable()->after('reply_method');
            $table->timestamp('replied_at')->nullable()->after('reply_message');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn([
                'client_mail_status',
                'admin_mail_status',
                'client_mail_reason',
                'admin_mail_reason',
                'reply_method',
                'reply_message',
                'replied_at',
            ]);
        });
    }
};

