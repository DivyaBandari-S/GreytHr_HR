<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftRotation extends Model
{
    use HasFactory;

    protected $fillable = ['shift_rotation_date','shift_type','day_type'];
}
