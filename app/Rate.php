<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $guarded = [];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public static function forAllUnits()
    {
        $units   = Unit::all();
        $rns     = new RNS;
        $counter = 0;

        foreach ($units as $unit) {
            $rates = $rns->ratesForUnit($unit->rns_id);
            self::attachToUnit($unit, $rates);
            usleep(20000);
        }
    }

    public static function attachToUnit($unit, $rates)
    {
        foreach ($rates as $rate) {
            self::createRate($unit, $rate);
        }
    }

    private static function createRate($unit, $rate)
    {
        foreach ($rate->RatesByUnit as $r) {
            Rate::updateOrCreate(
                [
                    'rns_unit_id' => $r->UnitId,
                    'start_date' => Carbon::parse($r->StartDate)->toDateTimeString()
                ],
                [
                    'unit_id'          => $unit->id,
                    'rns_unit_id'      => $r->UnitId,
                    'start_date'       => Carbon::parse($r->StartDate)->toDateTimeString(),
                    'end_date'         => Carbon::parse($r->EndDate)->toDateTimeString(),
                    'daily'            => $r->DailyRate,
                    'weekly'           => $r->WeeklyRate,
                    'monthly'          => $r->MonthlyRate,
                    'minimun_nights'   => $r->MinNoNights,
                    'blackout'         => $r->Blackout ?? 0,
                    'ignore_start_day' => $r->IgnoreStartDay ?? 0,
                ]
            );
        }
    }
}
