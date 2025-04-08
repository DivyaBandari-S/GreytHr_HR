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
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',10)->index(); // Employee ID
            $table->string('document_name'); // Document Name
            $table->string('category'); // Category (e.g., Accounts & Statutory, Address, etc.)
            $table->text('description')->nullable(); // Description
            $table->binary('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable(); // File Path to store the uploaded file
            $table->boolean('publish_to_portal')->default(false)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_documents');
    }
};
