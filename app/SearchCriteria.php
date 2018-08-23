<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchCriteria extends Model
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
            $images = $rns->imagesForUnit($unit->rns_id);
            if ($images) {
                self::attachToUnit($unit, $images);
            }
            usleep(250000);
        }
    }
}
