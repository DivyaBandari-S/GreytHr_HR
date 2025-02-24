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
        Schema::create('kudos', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('employee_id', 10);  // employee_id column as a string, matching emp_id in employees_details
            $table->string('recipient_id', 10);  // recipient_id column as a string, matching emp_id in employees_details

            // Define other fields
            $table->text('message');
            $table->json('recognize_type')->nullable();  // Recognition types
            $table->json('reactions')->nullable();  // Store reactions as JSON
            $table->enum('post_type', ['appreciations', 'buysellrent', 'companynews', 'events', 'everyone', 'hyderabad', 'technology'])
                  ->default('appreciations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kudos');
    }
};
