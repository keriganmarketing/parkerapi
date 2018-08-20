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
            if ($images) {
                self::attachToUnit($unit, $images);
            }
            usleep(250000);
        }
    }

    public static function attachToUnit($unit, $images)
    {
        foreach ($images as $image) {
            Image::updateOrCreate(
                [
                    'rns_unit_id' => $image->UnitId,
                    'name' => $image->ImageName
                ],
                [
                    'company_id'  => $image->CompanyId,
                    'unit_id'     => $unit->id,
                    'rns_unit_id' => $image->UnitId,
                    'name'        => $image->ImageName,
                    'description' => $image->ImageDesc,
                    'base_url'    => $image->ImageSource,
                    'url'         => $image->ImageSource . $image->ImageName,
                    'sort_order'  => $image->ImageSortNo
                ]
            );
        }
    }

    public static function changes()
    {
        $rns        = new RNS;
        $lastUpdate = self::getLastUpdatedImage();
        $changes    = $rns->getImageChanges($lastUpdate) ?? [];
        $counter    = 0;

        foreach ($changes as $changed) {
            $image = self::locateChangedImage($changed);
            $image->update([
                'company_id'  => $changed->CompanyId,
                'rns_unit_id' => $changed->UnitId,
                'name'        => $changed->ImageName,
                'description' => $changed->ImageDesc,
                'base_url'    => $changed->ImageSource,
                'sort_order'  => $changed->ImageSortNo,
                'url'         => $changed->ImageSource . $changed->ImageName
            ]);

            $counter++;
        }

        echo "Changed {$counter} images";
    }

    public static function getLastUpdatedImage()
    {
        return Carbon::parse(Image::all()->pluck('updated_at')->max())->format('m/d/Y g:i:s A');
    }

    public static function locateChangedImage($changed)
    {
        return Image::where('name', $changed->ImageName)->where('rns_unit_id', $changed->UnitId)->first();
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
