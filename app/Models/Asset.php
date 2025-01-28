<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
protected $table = 'assets';
protected $primaryKey = 'id';
protected $fillable = [
    'id',
    'emp_id',
    'asset_type',
    'active',
    'asset_status',
    'asset_details',
    'purchase_date',
    'asset_id',
    'brand',
    'model',
    'invoice_no',
    'original_value',
    'current_value',
    'warranty',
    'file_path',
    'file_name',
    'mime_type',
    'remarks',
];

// If you want to cast any attributes to a specific type
protected $casts = [
    'purchase_date' => 'date',
    'original_value' => 'decimal:2',
    'current_value' => 'decimal:2',
];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}
