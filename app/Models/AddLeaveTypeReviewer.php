<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddLeaveTypeReviewer extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_scheme',
        'leave_type',
        'reviewer_1',
        'reviewer_2',
    ];
}
