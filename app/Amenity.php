<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $guarded = [];
    protected $unitId;

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
        }
    }

    public static function attachToUnit($unit, $amenities)
    {
        foreach ($amenities as $amenity) {
            Amenity::create([
                'unit_id' => $unit->id,
                'rns_unit_id' => $amenity->UnitId,
                'rns_id' => $amenity->AmenityId,
                'name' => $amenity->Name,
                'description' => $amenity->Description,
                'sort_order' => $amenity->SortOrder
            ]);
        }
    }
}
