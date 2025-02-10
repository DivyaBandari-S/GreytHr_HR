<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockConfiguration extends Model
{
    use HasFactory;

    protected $fillable = ['from_date','to_date','category','effective_date','lock_criteria','criteria_name','updated_by','updated_lock_at'];
}
