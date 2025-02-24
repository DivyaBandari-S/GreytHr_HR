<?php

use Illuminate\Support\Facades\DB;
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
        Schema::create('emp_job_roles', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('job_id', 10)->nullable()->unique();
            $table->string('job_title')->nullable();
            $table->string('dept_id', 10)->nullable();
            $table->string('sub_dept_id', 10)->nullable();
            $table->timestamps();
            $table->foreign('dept_id')->references('dept_id')->on('emp_departments')->onDelete('cascade');
            $table->foreign('sub_dept_id')->references('sub_dept_id')->on('emp_sub_departments')->onDelete('cascade');
        });
        // Create the trigger for auto-generating job_id
        DB::unprepared('
    CREATE TRIGGER auto_generate_job_id
    BEFORE INSERT ON emp_job_roles
    FOR EACH ROW
    BEGIN
        DECLARE last_job_id INT;

        -- Get the last job_id inserted
        SELECT MAX(CAST(SUBSTRING(job_id, 2) AS UNSIGNED)) INTO last_job_id
        FROM emp_job_roles
        WHERE job_id LIKE "7%";

        -- If there is no last job_id, start with 700
        IF last_job_id IS NULL THEN
            SET last_job_id = 700;
        ELSE
            SET last_job_id = last_job_id + 1;
        END IF;

        -- Set the job_id
        SET NEW.job_id = CONCAT("7", last_job_id);
    END
');
    }

    /**
     * Reverse the migrations.
     */
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop trigger first
        DB::unprepared('DROP TRIGGER IF EXISTS auto_generate_job_id');

        Schema::dropIfExists('emp_job_roles');
    }
};
