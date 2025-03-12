<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwipeRecord extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    protected $fillable = ['emp_id', 'swipe_time', 'in_or_out', 'is_regularized', 'sign_in_device','device_name','device_id','swipe_location','swipe_remarks'];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }

}
