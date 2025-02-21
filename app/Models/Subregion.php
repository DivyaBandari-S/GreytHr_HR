<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subregion extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'translations', 'region_id', 'flag', 'wikiDataId'
    ];
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
}
