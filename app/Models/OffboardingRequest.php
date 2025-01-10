<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OffboardingRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
           'priority',
        
        'mobile',
        'mail',
        'file_paths',
        'cc_to',
    'last_working_day',

        'status_code','file_paths',
    ];

    protected $casts = [
        'file_paths' => 'array', // Automatically cast file_paths and cc_to as arrays
        'cc_to' => 'array',
    ];
    public function emp()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
    public function status()
{
    return $this->belongsTo(StatusType::class, 'status_code', 'status_code');
}
public function isImage()
{
    return 'data:image/jpeg;base64,' . base64_encode($this->attributes['file_path']);
}
public function getImageUrlsAttribute()
{
    // Assuming images are stored in the `file_paths` attribute as a JSON array
    return json_decode($this->file_paths, true); // Adjust based on your actual data structure
}
}
