<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Availability extends Model
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
            $availability = $rns->availabilityForUnit($unit->rns_id);
            self::attachToUnit($unit, $availability);
        }
    }

    public static function attachToUnit($unit, $availability)
    {
        foreach ($availability as $a) {
            Availability::updateOrCreate(
                ['rns_unit_id' => $a->UnitId],
                [
                'unit_id' => $unit->id,
                'company_id' => $a->CompanyId,
                'rns_unit_id' => $a->UnitId,
                'rns_id' => $a->AvailId,
                'arrival_date' => Carbon::parse($a->ArriveDate)->toDateTimeString(),
                'departure_date' => Carbon::parse($a->DepartDate)->toDateTimeString(),
            ]
            );
        }
    }
}
