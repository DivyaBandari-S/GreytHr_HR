<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hr', function (Blueprint $table) {
            $table->id();
            $table->string('hr_emp_id')->nullable()->default(null)->unique();
            $table->string('emp_id');
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->tinyInteger('role')->default(0); // Role column: 0 = user, 1 = admin, 2 = super admin (or other roles)
            $table->timestamps();
            $table->foreign('emp_id')
                ->references('emp_id') // Assuming the primary key of the companies table is 'id'
                ->on('employee_details')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_hr_emp_id BEFORE INSERT ON hr
        FOR EACH ROW
        BEGIN
            IF NEW.hr_emp_id IS NULL THEN
                SET @max_id := IFNULL((SELECT MAX(CAST(SUBSTRING(hr_emp_id, 4) AS UNSIGNED)) + 1 FROM hr), 10000);
                SET NEW.hr_emp_id = CONCAT('HR-', LPAD(@max_id, 5, '0'));
            END IF;
        END;
        SQL;

        DB::unprepared($triggerSQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the trigger before dropping the table
        DB::unprepared('DROP TRIGGER IF EXISTS generate_hr_emp_id;');

        // Now drop the table
        Schema::dropIfExists('hr');
    }
};
