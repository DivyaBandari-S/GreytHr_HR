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
        Schema::create('assets', function (Blueprint $table) {
            $table->string('emp_id');
            $table->enum('asset_type', ['Laptop', 'Keyboard', 'Monitor','Mouse']); 
            $table->enum('asset_status', ['Active', 'Pending', 'Completed']);  
            $table->string('asset_details'); 
            $table->date('issue_date'); 
            $table->string('asset_id')->unique();
            $table->date('valid_till')->nullable();
            $table->integer('asset_value'); 
            $table->string('returned_on'); // Path to attached file (nullable)
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->foreign('emp_id')
            ->references('emp_id')
            ->on('employee_details')
            ->onDelete('restrict')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
