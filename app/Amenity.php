<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $guarded = [];


    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public static function forAllUnits()
    {
        $units = Unit::all();
        $rns = new RNS;

        foreach ($units as $unit) {
            $amenities = $rns->amenitiesForUnit($unit->rns_id);
            self::attachToUnit($unit, $amenities);
            usleep(200000);
        }
    }

    public static function attachToUnit($unit, $amenities)
    {
        if (is_array($amenities)) {
            foreach ($amenities as $amenity) {
                Amenity::updateOrCreate(
                    [
                        'rns_unit_id' => $amenity->UnitId,
                        'rns_id'      => $amenity->AmenityId
                    ],
                    [
                        'unit_id'     => $unit->id,
                        'rns_unit_id' => $amenity->UnitId,
                        'rns_id'      => $amenity->AmenityId ?? 0,
                        'name'        => $amenity->Name ?? 'No name provided',
                        'description' => $amenity->Description ?? 'No Description provided',
                        'sort_order'  => $amenity->SortOrder ?? 0
                    ]
                );
            }
        }
    }
}
