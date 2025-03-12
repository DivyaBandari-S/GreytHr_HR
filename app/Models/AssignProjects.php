<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignProjects extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'emp_id',
        'project_name',
        'start_date',
        'end_date',
    ];
    public $incrementing = false;


    // Define relationships
    public function client()
    {
        return $this->belongsTo(ClientDetails::class, 'client_id','client_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id');
    }
}
