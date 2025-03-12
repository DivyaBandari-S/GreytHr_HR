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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->string('sender_id', 10);
            $table->string('receiver_id', 10)->nullable(); // Add receiver_id for each message
            $table->text('body')->nullable();
            $table->boolean('read')->default(0); // Read status
            $table->string('type')->nullable(); // Message type: text, image, video, etc.
            $table->json('media_path')->nullable(); // Path to uploaded file
            $table->tinyInteger('status')->default(1);
            $table->timestamp('last_time_message')->nullable();
            $table->timestamps();
            // Foreign key constraints
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            $table->foreign('sender_id')->references('emp_id')->on('employee_details')->onDelete('cascade');
            $table->foreign('receiver_id')->references('emp_id')->on('employee_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
