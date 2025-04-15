<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadBulkPhotos extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'zip_file',
        'uploaded_at',
        'uploaded_by',
        'log',
        'mime_type',
        'file_name'
    ];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'uploaded_by', 'emp_id');
    }
}
