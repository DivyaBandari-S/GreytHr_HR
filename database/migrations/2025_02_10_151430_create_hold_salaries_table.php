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
        Schema::create('hold_salaries', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id', 10);
            $table->string('payout_month', 15);
            $table->string('release_month', 15);
            $table->string('hold_reason');
            $table->string('release_reason');
            $table->text('remarks');
            $table->text('release_remarks');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_released')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hold_salaries');
    }
};
