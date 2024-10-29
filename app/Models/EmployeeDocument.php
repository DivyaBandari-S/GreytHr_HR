<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'document_name',
        'category',
        'mime_type', 
        'file_name', 
        'description',
        'file_path',
        'publish_to_portal',
    ];

    /**
     * Relationship to the Employee model.
     * Adjust 'Employee' with the actual employee model name if different.
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'id');
    }

    /**
     * Accessor to get the file's full URL.
     *
     * @return string
     */
    
}
