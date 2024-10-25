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
        Schema::create('companyShifts', function (Blueprint $table) {
            $table->id();
            $table->string('company_id');
            $table->string('shift_name');
            $table->string('shift_start_date');
            $table->string('shift_end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companyShifts');
    }
};
