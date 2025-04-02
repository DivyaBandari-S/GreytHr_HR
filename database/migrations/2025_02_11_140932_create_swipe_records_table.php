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
        Schema::create('swipe_records', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id',10);
            $table->timestamp('swipe_time');
            $table->string('in_or_out',10);
            $table->string('is_regularized',10)->nullable();
            $table->string('sign_in_device',100)->nullable();
            $table->string('device_name',100)->nullable();
            $table->string('device_id',100)->nullable();
            $table->string('swipe_location',100)->nullable();
            $table->string('swipe_remarks',100)->nullable();
            $table->foreign('emp_id')
            ->references('emp_id')
            ->on('employee_details')
            ->onDelete('restrict')
            ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swipe_records');
    }
};
