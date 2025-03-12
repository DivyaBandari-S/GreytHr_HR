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
        Schema::create('lock_configurations', function (Blueprint $table) {
            $table->id();
            $table->dateTime('from_date')->nullable();
            $table->dateTime('to_date')->nullable();
            $table->string('category')->nullable();
            $table->dateTime('effective_date')->nullable();
            $table->string('lock_criteria')->nullable();
            $table->string('criteria_name')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('updated_lock_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lock_configurations');
    }
};
