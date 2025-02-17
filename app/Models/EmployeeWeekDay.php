<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWeekDay extends Model
{
    use HasFactory;

    protected $fillable = ['emp_id', 'from_date', 'to_date','sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }


}
