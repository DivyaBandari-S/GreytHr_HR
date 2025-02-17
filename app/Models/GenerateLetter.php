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
        'employees',
        'joining_date',        // Joining Date
        'confirmation_date',   // Confirmation Date (if applicable)
        'remarks',             // Optional remarks
        'ctc',
        'status',
    ];
}
