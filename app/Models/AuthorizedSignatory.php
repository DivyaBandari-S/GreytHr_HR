<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizedSignatory extends Model
{
    use HasFactory;
    protected $fillable = ['first_name', 'last_name', 'designation', 'company', 'is_active', 'signature'];
}
