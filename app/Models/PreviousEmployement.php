<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviousEmployement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'emp_id',
        'company_name',
        'designation',
        'from_date',
        'to_date',
        'years_of_experience',
        'months_of_experience',
        'nature_of_duties',
        'leaving_reason',
        'pf_member_id',
        'last_drawn_salary',
    ];
}
