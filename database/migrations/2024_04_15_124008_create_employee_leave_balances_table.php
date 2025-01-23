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
        Schema::create('employee_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->integer('batch_id')->nullable();
            $table->json('leave_policy_id')->nullable();
            $table->foreign('emp_id')->references('emp_id')->on('employee_details')->onDelete('cascade')->onUpdate('cascade');
            $table->string('leave_scheme', 25)->default('General');
            $table->string('status')->default('Granted');
            $table->string('period',25)->nullable();
            $table->string('periodicity',25)->nullable();
            $table->string('granted_for_year', 10)->nullable();
            $table->boolean('is_lapsed')->default(false);
            $table->timestamp('lapsed_date')->nullable();
            $table->string('from_date')->nullable();
            $table->string('to_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_leave_balances');
    }
};