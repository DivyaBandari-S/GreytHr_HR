<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Hr extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    const ROLE_USER = 0;
    const ROLE_ADMIN = 1;
    const ROLE_SUPER_ADMIN = 2;
    protected $primaryKey = 'hr_emp_id';
    public $incrementing = false;
    protected $table = 'hr_employees';

    protected $fillable = [
        'hr_emp_id',
        'emp_id',
        'password',
        'is_active',
        'role',
    ];

    protected $casts = [
        'password',
    ];
    public function emp()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }

    public function LeaveBalance()
    {
        return $this->hasMany(EmployeeLeaveBalances::class, 'hr_emp_id', 'hr_emp_id');
    }
    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }
    public function hasRole($role)
    {
        return $this->role == $role;
    }

    public function getAdminFavModules(){
        return $this->hasMany(AdminFavoriteModule::class, 'hr_emp_id','hr_emp_id');
    }
}
