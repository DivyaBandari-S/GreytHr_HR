<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StopSalaries extends Model
{
    use HasFactory;
    protected $table = 'stop_salaries';
    protected $fillable = ['emp_id', 'payout_month', 'reason'];
}
