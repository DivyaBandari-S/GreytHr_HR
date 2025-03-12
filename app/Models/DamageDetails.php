<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DamageDetails extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
 
    protected $fillable = ['emp_id', 'damage_or_loss_date', 'damage_or_loss_reason', 'show_cause','show_cause_notice_date','name_of_the_person','amount_of_damage','no_of_installments','recovery_first_installment_date','recovery_last_installment_date','remarks'];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}
