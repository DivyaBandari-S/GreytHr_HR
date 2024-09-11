<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
protected $table = 'assets';
    protected $fillable = [
        'emp_id',
        'asset_type',
        'asset_status',
        'asset_details',
        'issue_date',
        'asset_id',
        'valid_till',
        'asset_value',
        'returned_on',
        'cc_to',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}
