<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = [];

    public static function forAllUnits()
    {
        $units = Unit::all();
        $rns = new RNS;

        foreach ($units as $unit) {
            $images = $rns->imagesForUnit($unit->rns_id);
            self::attachToUnit($unit, $images);
        }
    }

    public static function attachToUnit($unit, $images)
    {
        foreach ($images as $image) {
            Image::create([
                'company_id'  => $image->CompanyId,
                'unit_id'     => $unit->id,
                'rns_unit_id' => $image->UnitId,
                'name'        => $image->ImageName,
                'description' => $image->ImageDesc,
                'base_url'    => $image->ImageSource,
                'url'         => $image->ImageSource . $image->ImageName,
                'sort_order'  => $image->ImageSortNo
            ]);
        }
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
