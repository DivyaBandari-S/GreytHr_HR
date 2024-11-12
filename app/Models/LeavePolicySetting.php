<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePolicySetting extends Model
{
    use HasFactory;
    protected $table = 'leave_policy_settings';
    protected $fillable = [
        'leave_name',
        'grant_days',
        'leave_frequency',
        'is_active',
        'leave_code',
        'company_id',
    ];
}
