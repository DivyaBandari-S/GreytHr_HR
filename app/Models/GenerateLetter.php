<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenerateLetter extends Model
{
    use HasFactory;
    protected $fillable = [
        'template_name',       // Letter Template
        'serial_no',           // Auto-generated Serial Number
        'authorized_signatory',// Authorized Signatory
        'employee_name',       // Employee Full Name
        'employee_id',         // Employee ID
        'employee_address',    // Employee Address
        'joining_date',        // Joining Date
        'confirmation_date',   // Confirmation Date (if applicable)
        'letter_content',      // Generated Letter Content
        'remarks',             // Optional remarks
    ];
}
