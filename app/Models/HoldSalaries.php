<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoldSalaries extends Model
{
    use HasFactory;
    protected $table = 'hold_salaries';
    protected $fillable = ['emp_id', 'payout_month', 'hold_reason','remarks','status'];


}
