<?php

namespace App;

use App\Unit;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
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
            $detail = $rns->detailsForUnit($unit->rns_id);
            if ($detail != 'Sausage') {
                self::attachToUnit($unit, $detail);
            } else {
                echo $detail . ' #'. $counter .  PHP_EOL;
                $counter++;
            }
            usleep(20000);
        }
    }

    public static function attachToUnit($unit, $detail)
    {
        Detail::create([
            'unit_id'              => $unit->id,
            'rns_unit_id'          => $detail->UnitId,
            'location_id'          => $detail->LocationId,
            'company_id'           => $detail->CompanyId,
            'prop_name'            => $detail->PropName,
            'prop_number'          => $detail->PropNumber,
            'address'              => $detail->Address,
            'city'                 => $detail->City,
            'state'                => $detail->State,
            'zip'                  => $detail->Zip,
            'beds'                 => $detail->Bed,
            'baths'                => $detail->Bath,
            'sleeps'               => $detail->Sleeps,
            'inactive'             => $detail->Inactive,
            'turn_day'             => $detail->TurnDay,
            'description'          => $detail->Description,
            'geocode'              => $detail->Geocode,
            'unit_types_list_id'   => $detail->UnitTypesListId,
            'subdivisions_id'      => $detail->SubdivisionsId,
            'reservation_group_id' => $detail->ReservationGroupId,
            'finance_group_id'     => $detail->FinanceGroupId,
            'persons_per_rental'   => $detail->PersonsPerRental,
        ]);
    }
}
