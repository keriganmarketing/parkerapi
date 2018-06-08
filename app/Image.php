<?php

namespace App;

use Carbon\Carbon;
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

    public static function changes()
    {
        $lastUpdate = Carbon::parse(Image::all()->pluck('updated_at')->max())->format('m/d/Y g:i:s A');
        $rns = new RNS;
        $changes = $rns->getImageChanges($lastUpdate);
        $counter = 0;

        foreach ($changes as $changed) {
            $image = Image::where('rns_unit_id', $changed->UnitId)->first();

            $image->update([
                'company_id' => $changed->CompanyId,
                'rns_unit_id' => $changed->UnitId,
                'name' => $changed->ImageName,
                'description' => $changed->ImageDesc,
                'base_url' => $changed->ImageSource,
                'sort_order' => $changed->ImageSortNo,
                'url' => $changed->ImageSource . $changed->ImageName
            ]);

            $counter++;
        }

        echo "Changed {$counter} images";
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
