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
        Schema::create('emp_salaries', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->smallInteger('sal_id');
            $table->smallInteger('bank_id');
            $table->string('salary'); // Use string for encrypted salary
            $table->string('month_of_sal');
            $table->date('effective_date');
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->tinyInteger('is_payslip')->default(0);
            $table->foreign('sal_id')->references('id')->on('salary_revisions')->onDelete('cascade');
            $table->foreign('bank_id')->references('id')->on('emp_bank_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_salaries');
    }
};
