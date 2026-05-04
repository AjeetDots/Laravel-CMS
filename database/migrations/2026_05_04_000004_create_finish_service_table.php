<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('finish_service', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('finish_id')->constrained()->cascadeOnDelete();
            $table->primary(['service_id', 'finish_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('finish_service');
    }
};
