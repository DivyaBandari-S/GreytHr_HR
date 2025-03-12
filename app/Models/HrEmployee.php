<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrEmployee extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the default
    protected $table = 'hr_employees';

    // Define which attributes are mass assignable
    protected $fillable = [
        'hr_emp_id', 
        'emp_id', 
        'email', 
        'employee_name', 
        'password', 
        'status', 
        'role'
    ];

    // Define the fields that should be cast to specific types
    protected $casts = [
        'status' => 'boolean',
    ];

    // Set up the relationship with the employee details (foreign key)
    public function employeeDetails()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }

    // Automatically assign the hr_emp_id if it's not provided
    // protected static function booted()
    // {
    //     static::creating(function ($employee) {
    //         if (empty($employee->hr_emp_id)) {
    //             // Trigger logic to auto-generate hr_emp_id
    //             $maxId = self::max('id') + 1;
    //             $employee->hr_emp_id = 'HR-' . str_pad($maxId, 5, '0', STR_PAD_LEFT);
    //         }
    //     });
    // }
}
