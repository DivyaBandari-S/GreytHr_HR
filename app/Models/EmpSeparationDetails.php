<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpSeparationDetails extends Model
{
    use HasFactory;
    protected $table = 'emp_separation_details';
    protected $fillable = [
        'emp_id',
        'hr_emp_id',
        'separation_mode',
        'resignation_submitted_on',
        'reason',
        'notice_required',
        'exclude_final_settlement',
        'notice_period',
        'short_fall_notice_period',
        'tentative_date',
        'remarks',
        'other_date',
        'notes',
        'exit_interview_date',
        'leaving_date',
        'settled_date',
        'is_left_org',
        'is_served_notice',
        'fit_to_rehire',
        'alt_email_id',
        'alt_mbl_no',
        'date_of_demise',
        'retired_date',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
    public function HRemployee()
    {
        return $this->belongsTo(Hr::class, 'hr_emp_id', 'hr_emp_id');
    }
}
