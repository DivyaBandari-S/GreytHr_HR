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
        Schema::create('upload_bulk_photos', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('Completed');
            $table->dateTime('uploaded_at')->nullable();
            $table->string('uploaded_by');
            $table->binary('zip_file')->nullable();
            $table->string('mime_type')->nullable();
            // Add file_name column to store the original file name
            $table->string('file_name')->nullable();
            $table->text('log')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_bulk_photos');
    }
};
