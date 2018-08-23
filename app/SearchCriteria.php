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
            $searchCriteria = $rns->searchCriteriaForUnit($unit->rns_id);
            if ($searchCriteria) {
                self::attachToUnit($unit, $searchCriteria);
            }
            usleep(250000);
        }
    }

    public static function attachToUnit($unit, $searchCriteria)
    {
        foreach ($searchCriteria as $sc) {
            SearchCriteria::updateOrCreate(
                [
                    'rns_unit_id' => $sc->UnitId,
                    'name' => $sc->Name
                ],
                [
                    'unit_id'     => $unit->id,
                    'rns_unit_id' => $sc->UnitId,
                    'rns_id'      => $sc->Id,
                    'name'        => $sc->Name,
                    'sort_order'  => $sc->SortOrder
                ]
            );
        }
    }
}
