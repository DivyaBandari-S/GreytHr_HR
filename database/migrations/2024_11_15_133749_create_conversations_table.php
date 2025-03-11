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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->nullable()->unique(); // For group chats
            $table->string('group_name')->nullable(); // Group name for group chats
            $table->string('sender_id', 10)->nullable(); // For one-to-one chats
            $table->string('receiver_id', 10)->nullable(); // For one-to-one chats
            $table->timestamp('last_time_message')->nullable(); // Timestamp for the last message sent
            $table->timestamps();
            // Foreign key constraints
            $table->foreign('sender_id')->references('emp_id')->on('employee_details')->onDelete('cascade');
            $table->foreign('receiver_id')->references('emp_id')->on('employee_details')->onDelete('cascade');
        });

         // Create the pivot table for many-to-many relationship between conversations and employees
         Schema::create('group_conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id'); // FK for the conversation
            $table->string('emp_id', 10); // FK for the employee
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            $table->foreign('emp_id')->references('emp_id')->on('employee_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_conversations');
        Schema::dropIfExists('conversations');
    }
};
