<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveYearEndProcess extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'leaves_details',
        'lapsed_date',
        'process_reason',
        'status',
    ];
}
