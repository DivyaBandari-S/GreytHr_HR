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
        Schema::create('emp_separation_details', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id', 10)->unique()->nullable();
            $table->string('hr_emp_id', 10)->nullable();
            $table->string('separation_mode',25)->nullable();
            $table->date('other_date')->nullable();
            $table->date('resignation_submitted_on')->nullable();
            $table->text('reason')->nullable();
            $table->boolean('notice_required')->default(0)->nullable();
            $table->boolean('exclude_final_settlement')->default(0)->nullable();
            $table->string('notice_period', 10)->nullable();
            $table->string('short_fall_notice_period',10)->nullable();
            $table->date('tentative_date')->nullable();
            $table->text('remarks')->nullable();
            $table->text('notes')->nullable();
            $table->date('exit_interview_date')->nullable();
            $table->date('leaving_date')->nullable();
            $table->date('settled_date')->nullable();
            $table->boolean('is_left_org')->default(0)->nullable();
            $table->boolean('is_served_notice')->default(0)->nullable();
            $table->boolean('fit_to_rehire')->default(0)->nullable();
            $table->string('alt_email_id',100)->nullable();
            $table->string('alt_mbl_no',15)->nullable();
            $table->date('date_of_demise')->nullable();
            $table->date('retired_date')->nullable();
            $table->timestamps();
            $table->foreign('emp_id')
                ->references('emp_id')
                ->on('employee_details')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_separation_details');
    }
};
