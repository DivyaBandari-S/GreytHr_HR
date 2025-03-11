<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientProjects extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'project_name',
        'project_description',
        'project_start_date',
        'project_end_date',
    ];

    // Relationship with the client
    public function client()
    {
        return $this->belongsTo(ClientDetails::class, 'client_id', 'client_id');
    }
}
