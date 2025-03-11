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

        Schema::create('salary_revisions', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id', 10);
            $table->string('current_ctc')->nullable();
            $table->string('revised_ctc')->nullable();
            $table->date('revision_date')->nullable();
            $table->text('revision_type')->nullable();
            $table->string('reason')->nullable();
            $table->string('payout_month',20);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            // Define the foreign key relationship
            $table->foreign('emp_id')->references('emp_id')->on('employee_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_revisions');
    }
};
