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
        Schema::create('company_types', function (Blueprint $table) {
            $table->id(); // Default bigIncrements
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        Schema::create('industry_types', function (Blueprint $table) {
            $table->id(); // Changed from mediumIncrements to id()
            $table->string('name', 100)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industry_types');
        Schema::dropIfExists('company_types'); // Added this line to properly rollback both tables
    }
};
