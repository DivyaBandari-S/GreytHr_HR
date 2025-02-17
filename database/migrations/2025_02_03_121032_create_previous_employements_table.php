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
        Schema::create('previous_employements', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('company_name');
            $table->string('designation');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('years_of_experience')->nullable();
            $table->integer('months_of_experience')->nullable();
            $table->text('nature_of_duties')->nullable();
            $table->text('leaving_reason')->nullable();
            $table->string('pf_member_id')->nullable();
            $table->decimal('last_drawn_salary', 10, 2)->nullable();
            $table->timestamps();
            $table->foreign('emp_id')
            ->references('emp_id')
            ->on('employee_details')
            ->onDelete('restrict')
            ->onUpdate('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previous_employements');
    }
};
