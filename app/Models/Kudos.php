<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kudos extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'recipient_id',
        'message',
        'recognize_type',
        'post_type',
        'reactions',
    ];
    protected $casts = [
        'recognize_type' => 'array',  // Cast the recognition types as an array
        'reactions' => 'array',  // Cast reactions as an array
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id');
    }

    // Define the relationship to the Employee model for the recipient (recipient_id)
    public function recipient()
    {
        return $this->belongsTo(EmployeeDetails::class, 'recipient_id');
    }

    // Define the relationship with the Reaction model
    public function reactions()
    {
        return $this->hasMany(KudosReactions::class);
    }

    // Add a helper function to add reactions
    public function addReaction($reactionType, $employeeId)
    {
        return $this->reactions()->create([
            'reaction' => $reactionType,
            'employee_id' => $employeeId,
        ]);
    }

}

