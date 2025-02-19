<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminFavoriteModule extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'hr_emp_id',
        'module_name',
        'module_category',
        'is_starred'
    ];
    public function getHrDetails() {
        return $this->belongsTo(Hr::class, 'hr_emp_id','hr_emp_id');
    }
}
