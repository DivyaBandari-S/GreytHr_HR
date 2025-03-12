<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'iso3',
        'numeric_code',
        'iso2',
        'phonecode',
        'capital',
        'currency',
        'currency_name',
        'currency_symbol',
        'tld',
        'native',
        'region',
        'region_id',
        'subregion',
        'subregion_id',
        'nationality',
        'timezones',
        'translations',
        'latitude',
        'longitude',
        'emoji',
        'emojiU',
        'flag',
        'wikiDataId'
    ];
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function subregion()
    {
        return $this->belongsTo(Subregion::class, 'subregion_id');
    }
    public function states()
    {
        return $this->hasMany(State::class, 'country_id');
    }
}
