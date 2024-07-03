<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addcomment extends Model
{
    use HasFactory;
    protected $table = 'add_comments';
    protected $fillable = [
        'emp_id',
        'card_id',
        'addcomment',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
}