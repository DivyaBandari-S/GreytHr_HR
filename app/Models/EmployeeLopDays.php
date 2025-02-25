<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLopDays extends Model
{
    use HasFactory;
    protected $table = 'employee_lop_days';
    protected $fillable = ['emp_id', 'lop_days','payout_month', 'remarks'];

}
