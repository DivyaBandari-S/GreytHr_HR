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
        Schema::create('fine_and_damages', function (Blueprint $table) {
            $table->id();
             
            $table->string('emp_id',10);
           
            $table->date('offence_date')->nullable();
            $table->string('act_or_omission')->nullable();
            $table->string('is_show_cause')->nullable();
            $table->date('show_cause_date')->nullable();
            $table->string('name_of_the_person')->nullable();
            $table->integer('amount_of_fine')->nullable();
            $table->date('fine_realized_date')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fine_and_damages');
    }
};
