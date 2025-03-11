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
        Schema::create('feed_back_models', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->enum('feedback_type', ['request', 'give']); // Type of feedback
            $table->string('feedback_to', 10); // User receiving feedback
            $table->string('feedback_from', 10); // User giving feedback
            $table->text('feedback_message');
            $table->boolean('is_draft')->default(false);
            $table->tinyInteger('status')->default(1);
            $table->boolean('is_accepted')->default(false);
            $table->boolean('is_declined')->default(false);
            $table->text('replay_feedback_message')->nullable();
            $table->timestamps();

            // Foreign keys (assuming referencing users table)
            $table->foreign('feedback_to')->references('emp_id')->on('employee_details')->onDelete('cascade');
            $table->foreign('feedback_from')->references('emp_id')->on('employee_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_back_models');
    }
};
