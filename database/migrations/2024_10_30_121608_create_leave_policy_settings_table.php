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
        Schema::create('leave_policy_settings', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('leave_name',50);
            $table->integer('grant_days');
            $table->enum('leave_frequency', ['Annual', 'Monthly'])->nullable();
            $table->integer('is_active')->default(1);
            $table->string('leave_code', 10)->unique();
            $table->json('company_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_policy_settings');
    }
};
