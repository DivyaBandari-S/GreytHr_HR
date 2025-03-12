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
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id',10);
            $table->string('hr_emp_id',10)->nullable();length:
            $table->integer('batch_id',10)->nullable();
            $table->json('leave_policy_id')->nullable();
            $table->foreign('emp_id')->references('emp_id')->on('employee_details')->onDelete('cascade')->onUpdate('cascade');
            $table->string('leave_scheme', 25)->default('General');
            $table->string('status')->default('Granted');
            $table->string('period',25)->nullable();
            $table->string('periodicity',25)->nullable();
            $table->string('granted_for_year', 10)->nullable();
            $table->boolean('is_lapsed')->default(false);
            $table->timestamp('lapsed_date')->nullable();
            $table->string('from_date',25)->nullable();
            $table->string('to_date',25)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('hr_emp_id')
                ->references('hr_emp_id')
                ->on('hr_employees')
                ->onDelete('restrict')
                ->onUpdate('cascade');

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
