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
        Schema::create('damage_details', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id',10);
            $table->date('damage_or_loss_date')->nullable();
            $table->string('damage_or_loss_reason')->nullable();
            $table->string('show_cause')->nullable();
            $table->date('show_cause_notice_date')->nullable();
            $table->string('name_of_the_person')->nullable();
            $table->integer('amount_of_damage')->nullable();
            $table->integer('no_of_installments')->nullable();
            $table->date('recovery_first_installment_date')->nullable();
            $table->date('recovery_last_installment_date')->nullable();
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
        Schema::dropIfExists('damage_details');
    }
};
