<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FineAndDamage extends Model
{
   

    use HasFactory;
 
    use SoftDeletes;
 
    protected $dates = ['deleted_at'];
 
    protected $fillable = ['emp_id', 'offence_date', 'act_or_omission', 'is_show_cause','show_cause_date','name_of_the_person','amount_of_fine','fine_realized_date','remarks'];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}
