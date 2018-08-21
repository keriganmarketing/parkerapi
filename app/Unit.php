<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'company_id', 'rns_id', 'number', 'name'
    ];

    const AMENITIES = [
        'pool' => 45,
        'internet' => 22,
        'screened_in_porch' => 46,
        'pet_friendly' => 50,
        'elevator' => 17
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

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public static function searchFor(Request $request)
    {
        $name     = $request->name ?? null;
        $location = $request->location ?? null;
        $type     = $request->type ?? null;

        return Unit::with('amenities', 'images', 'availability', 'details')
                    ->when($type, function ($query) use ($type) {
                        return $query->where('type', $type);
                    })->when($location, function ($query) use ($location) {
                        return $query->where('location', $location);
                    })->get();
    }
}
