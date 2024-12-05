<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayMasterList extends Model
{
    use HasFactory;
    protected $fillable = ['occasion', 'date', 'day', 'state'];
}
