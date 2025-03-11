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
        Schema::create('full_settlements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('emp_id', 10);
            $table->string('payout_month',15);
            $table->string('serial_number',50);
            $table->date('leaving_date');
            $table->date('settlement_date');
            $table->date('processed_on');
            $table->date('net_pay',15);
            $table->text('remarks');
            $table->tinyInteger('is_lock')->default(1);
            $table->tinyInteger('status')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('full_settlements');
    }
};
