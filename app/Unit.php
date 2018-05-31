<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'company_id', 'rns_id', 'number', 'name'
    ];

    public function amenities()
    {
        return $this->hasMany(Amenity::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }
}
