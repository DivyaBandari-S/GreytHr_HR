<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('offboarding_requests', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id', 10);
            $table->enum('priority', ['low', 'medium', 'high'])->default('low');
            $table->string('mobile')->nullable();
            $table->string('mail')->nullable();
            $table->json('file_paths')->nullable();
            $table->json('cc_to')->nullable();
            $table->date('last_working_day')->nullable();
            $table->tinyInteger('status_code')->default(8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offboarding_requests');
    }
};
