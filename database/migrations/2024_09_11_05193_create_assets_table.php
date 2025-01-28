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
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id');
            $table->enum('asset_type', ['Laptop', 'Keyboard', 'Monitor','Mouse']); 
            $table->enum('active', ['Active', 'InActive'])->default('Active')->nullable();  
             $table->enum('asset_status', ['All','Available','Damaged','Decommissioned','Issued','Lost','Returned','Under Repair'])->default('All');
            $table->string('asset_details'); 
            $table->date('purchase_date'); 
            $table->string('asset_id')->unique();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('invoice_no')->unique()->nullable();
            
            $table->decimal('original_value', 10, 2)->nullable();
            $table->decimal('current_value', 10, 2)->nullable();
            
            $table->enum('warranty', ['Yes', 'No']); 
         
           // Path to attached file (nullable)
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
