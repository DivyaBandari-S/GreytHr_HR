<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KudosReactions extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id', // The employee who reacted
        'reaction',    // The type of reaction (e.g., 'thumbs_up', 'heart', etc.)
    ];
    public function kudos()
    {
        return $this->belongsTo(Kudos::class);
    }

    // Define the relationship to the Employee model for the employee who reacted
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class);
    }
}

