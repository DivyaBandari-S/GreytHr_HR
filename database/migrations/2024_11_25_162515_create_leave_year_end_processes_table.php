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
        Schema::create('leave_year_end_processes', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->json('leaves_details')->nullable();
            $table->string('lapsed_date')->nullable();
            $table->string('process_reason')->nullable();
            $table->string('status')->default('Lapsed');
            $table->timestamps();
            $table->foreign('emp_id')->references('emp_id')->on('employee_details')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_year_end_processes');
    }
};
