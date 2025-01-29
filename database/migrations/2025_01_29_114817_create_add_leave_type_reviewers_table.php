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
        Schema::create('add_leave_type_reviewers', function (Blueprint $table) {
            $table->id();
            $table->string('leave_scheme',100)->default('general');
            $table->string('leave_type', 100)->nullable();
            $table->string('reviewer_1')->nullable();
            $table->string('reviewer_2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_leave_type_reviewers');
    }
};
