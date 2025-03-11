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
        Schema::create('notifications', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id',10);
            $table->integer('chatting_id')->nullable();
            $table->string('task_name',100)->nullable();
            $table->string('assignee',100)->nullable();
            $table->json('is_birthday_read')->nullable();
            $table->string('leave_status',20)->default('Pending');
            $table->string('regularisation_status',20)->default(5);
            $table->json('regularisation_entries');
            $table->string('applying_to')->nullable();
            $table->json('cc_to')->nullable();
            $table->string('receiver_id',10)->nullable();
            $table->string('notification_type',20);
            $table->dateTime('message_read_at')->nullable();
            $table->longText('body')->nullable();
            $table->boolean('is_read')->default(false);
            $table->enum('leave_type', ['Casual Leave', 'Sick Leave', 'Loss Of Pay','Maternity Leave','Casual Leave Probation','Marriage Leave','Petarnity Leave', 'Work From Home' ]);
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
        Schema::dropIfExists('notifications');
    }
};
