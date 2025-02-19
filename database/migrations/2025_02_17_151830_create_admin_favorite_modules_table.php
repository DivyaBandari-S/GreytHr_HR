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
        Schema::create('admin_favorite_modules', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id', 25);
            $table->string('hr_emp_id', 10)->nullable();
            $table->string('module_name', 100)->nullable();
            $table->string('module_category', 100)->nullable();
            $table->boolean('is_starred')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_favorite_modules');
    }
};
