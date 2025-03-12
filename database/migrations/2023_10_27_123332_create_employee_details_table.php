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
        Schema::create('employee_details', function (Blueprint $table) {
            $table->string('emp_id',10)->primary();
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHER'])->nullable();
            $table->string('email',100)->unique()->nullable();
            $table->json('company_id');
            $table->binary('image')->nullable();
            $table->string('dept_id',10)->nullable();
            $table->string('sub_dept_id',10)->nullable();
            $table->date('hire_date')->nullable();
            $table->string('employee_type',100)->nullable();
            $table->string('job_role',100)->nullable();
            $table->string('manager_id',10)->nullable();
            $table->string('dept_head',10)->nullable();
            $table->enum('role', ['user', 'admin', 'super_admin'])->default('user'); //Define ENUM for roles
            $table->enum('employee_status', ['active', 'on-leave', 'terminated', 'resigned', 'on-probation'])->default('active');
            $table->string('emergency_contact',20)->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->enum('inter_emp', ['yes', 'no']);
            $table->string('job_location',50)->nullable();
            $table->string('experience',20)->nullable();
            $table->string('time_zone',50)->nullable();
            $table->string('is_starred',10)->nullable();
            $table->string('job_mode',20)->nullable();
            $table->json('emp_domain')->nullable();
            $table->string('notice_period',10)->nullable();
            $table->date('last_working_date')->nullable();
            $table->date('resignation_date')->nullable();
            $table->text('resignation_reason')->nullable();
            $table->string('extension',10)->nullable();
            $table->string('shift_type',10)->nullable();
            $table->json('shift_entries')->nullable();
            // $table->string('shift_start_time')->nullable();
            // $table->string('shift_end_time')->nullable();
            $table->string('probation_Period',10)->default('30');
            $table->date('confirmation_date')->nullable();
            $table->string('referral',100)->nullable();
            $table->string('service_age',20)->nullable();
            $table->foreign('dept_id')->references('dept_id')->on('emp_departments')->onDelete('cascade');
            $table->foreign('sub_dept_id')->references('sub_dept_id')->on('emp_sub_departments')->onDelete('cascade');
            $table->rememberToken();



            $table->timestamps();
        });

        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_emp_id BEFORE INSERT ON employee_details FOR EACH ROW
        BEGIN
            DECLARE company_count INT;

            IF TRIM(IFNULL(NEW.company_name, '')) != '' THEN
                -- Check if the company name has more than one word
                IF LOCATE(' ', NEW.company_name) > 0 THEN
                    -- More than one word, take the first word and use prefixes for the remaining words
                    SET @first_word = UPPER(SUBSTRING_INDEX(NEW.company_name, ' ', 1));
                    SET @remaining_words = UPPER(SUBSTRING(SUBSTRING_INDEX(NEW.company_name, ' ', -1), 1, 3));

                    -- Count the number of existing employees with the same company name
                    SELECT COUNT(*) INTO company_count FROM employee_details WHERE company_name = NEW.company_name;
                    SET NEW.emp_id = CONCAT(@first_word, '-', @remaining_words, '-', LPAD(company_count + 1, 4, '0'));
                ELSE
                    -- Single word, use the entire word as emp_id

                    -- Check if the company is new or already exists
                    SELECT COUNT(*) INTO company_count FROM employee_details WHERE company_name = NEW.company_name;

                    IF company_count > 0 THEN
                        -- Existing company, increment the counter
                        SELECT MAX(SUBSTRING_INDEX(emp_id, '-', -1)) INTO company_count FROM employee_details WHERE company_name = NEW.company_name;
                        SET NEW.emp_id = CONCAT(UPPER(NEW.company_name), '-', LPAD(company_count + 1, 4, '0'));
                    ELSE
                        -- New company, start the counter from 0001
                        SET NEW.emp_id = CONCAT(UPPER(NEW.company_name), '-0001');
                    END IF;
                END IF;
            ELSE
                -- Set the emp_id to null if the company name is empty
                SET NEW.emp_id = NULL;
            END IF;
        END;
        SQL;

         DB::unprepared($triggerSQL);



        // Add a unique constraint for mobile_number and alternate_mobile_number
        DB::unprepared('ALTER TABLE employee_details ADD CONSTRAINT unique_mobile_numbers UNIQUE (mobile_number, alternate_mobile_number)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $triggerSQL = <<<SQL
        DROP TRIGGER IF EXISTS generate_emp_id;
        SQL;
        DB::unprepared($triggerSQL);
        Schema::dropIfExists('employee_details');
    }
};
