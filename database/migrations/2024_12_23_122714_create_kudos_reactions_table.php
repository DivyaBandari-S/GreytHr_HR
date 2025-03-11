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
        Schema::create('kudos_reactions', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('employee_id');  // Instead of foreignId, use string or integer if necessary.
            $table->string('reaction');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kudos_reactions');
    }
};
