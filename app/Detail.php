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
            'rns_unit_id'          => $detail->UnitId ?? null,
            'location_id'          => $detail->LocationId ?? null,
            'company_id'           => $detail->CompanyId ?? null,
            'prop_name'            => $detail->PropName ?? null,
            'prop_number'          => $detail->PropNumber ?? null,
            'address'              => $detail->Address ?? null,
            'city'                 => $detail->City ?? null,
            'state'                => $detail->State ?? null,
            'zip'                  => $detail->Zip ?? 'No Zip Specified',
            'beds'                 => $detail->Bed ?? null,
            'baths'                => $detail->Bath ?? null,
            'sleeps'               => $detail->Sleeps ?? null,
            'inactive'             => $detail->Inactive ?? null,
            'turn_day'             => $detail->TurnDay ?? null,
            'description'          => $detail->Description ?? null,
            'geocode'              => $detail->Geocode ?? null,
            'unit_types_list_id'   => $detail->UnitTypesListId ?? null,
            'subdivisions_id'      => $detail->SubdivisionsId ?? null,
            'reservation_group_id' => $detail->ReservationGroupId ?? null,
            'finance_group_id'     => $detail->FinanceGroupId ?? null,
            'persons_per_rental'   => $detail->PersonsPerRental ?? null,
        ]);
    }
}
