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
        Schema::create('holiday_calendars', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('day',10);
            $table->date('date');
            $table->string('month',10);
            $table->string('year',10);
            $table->string('festivals',50)->nullable();
            $table->smallInteger('status')->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holiday_calendars');
    }
};
