<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Unit extends Model
{
    protected $fillable = [
        'company_id', 'rns_id', 'number', 'name'
    ];

    public function amenities()
    {
        return $this->hasMany(Amenity::class);
    }

    public function availability()
    {
        return $this->hasMany(Availability::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function details()
    {
        return $this->hasMany(Detail::class);
    }

    public static function searchFor(Request $request)
    {
        $name     = $request->name ?? null;
        $location = $request->location ?? null;
        $type     = $request->type ?? null;

        return Unit::with('amenities', 'images', 'availability')
                    ->when($type, function ($query) use ($type) {
                        return $query->where('type', $type);
                    })->when($location, function ($query) use ($location) {
                        return $query->where('location', $location);
                    })->get();

    }
}
