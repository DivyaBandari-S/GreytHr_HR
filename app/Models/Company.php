<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable
{
    use HasFactory;
    // protected $primaryKey = 'company_id';
    protected $fillable = [
        'company_id',
        'company_name',
        'ceo_name',
        'company_website',
        'company_type',
        'company_industry',
        'company_description',
        'company_present_address',
        'company_permanent_address',
        'country',
        'state',
        'time_zone',
        'currency',
        'branch_locations',
        'company_registration_date',
        'company_registration_no',
        'pan_no',
        'pf_no',
        'tan_no',
        'lin_no',
        'gst_no',
        'esi_no',
        'company_logo',
        'contact_email',
        'email_domain',
        'parent_company_id',
        'is_parent',
        'contact_phone',
        'status'
    ];

    protected $casts = [
        'state' => 'array',
        'company_registration_date' => 'date',
        'branch_locations' => 'array',
    ];

}
