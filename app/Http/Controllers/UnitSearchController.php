<?php

namespace App\Http\Controllers;

use App\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UnitSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $booked   = 'availability'; //change the verbiage to reflect what it actually is
        $name     = $request->name ?? null;
        $checkIn  = isset($request->checkIn) && $request->checkIn !== '' ? Carbon::parse($request->checkIn) : null;
        $checkOut = isset($request->checkOut) && $request->checkOut !== '' ? Carbon::parse($request->checkOut) : null;
        $location = $request->location ?? null;
        $type     = $request->type ?? null;
        $dock     = $request->dock == 'true' ?? null;
        $pool     = $request->pool == 'true' ?? null;
        $canal    = $request->canal == 'true' ?? null;
        $internet = $request->internet == 'true' ?? null;
        $linens   = $request->linens == 'true' ?? null;
        $pets     = $request->pets == 'true' ?? null;

        $units = Unit::with('searchCriteria', 'details', 'rates', 'amenities')
                    ->with(['images' => function ($query) {
                        return $query->where('sort_order', 1);
                    }])
                    ->when($checkIn, function($query) use ($checkIn, $checkOut, $booked) {
                        return $query->whereDoesntHave($booked, function ($query) use ($checkIn, $checkOut) {
                            return $query->whereDate('arrival_date', '<=', $checkIn)->whereDate('departure_date', '>=', $checkIn);
                        });
                    })
                    ->when($checkOut, function($query) use ($checkIn, $checkOut, $booked) {
                        return $query->whereDoesntHave($booked, function ($query) use ($checkIn, $checkOut) {
                            return $query->whereDate('arrival_date', '>=', $checkOut)->whereDate('departure_date', '<=', $checkOut);
                        });
                    })
                   ->when($name, function ($query) use ($name) {
                        return $query->where('name', 'like', '%'. $name . '%');
                   })
                   ->when($location, function ($query) use ($location) {
                        return $query->whereHas('searchCriteria', function ($query) use ($location){
                            return $query->where('name', 'like', $location);
                        });
                   })
                   ->when($type, function ($query) use ($type) {
                        return $query->whereHas('searchCriteria', function ($query) use ($type){
                            return $query->where('name', 'like', $type);
                        });
                   })
                   ->when($dock, function ($query) {
                       return $query->whereHas('amenities', function ($query) {
                           return $query->where('rns_id', 47)->where('description', '!=', 'No');
                       });
                   })
                   ->when($pool, function ($query) {
                       return $query->whereHas('amenities', function ($query) {
                           return $query->where('rns_id', 45)->where('description', '!=', 'No');
                       });
                   })
                   ->when($canal, function ($query) {
                        return $query->whereHas('searchCriteria', function ($query)  {
                            return $query->where('rns_id', 39);
                        });
                   })
                   ->when($internet, function ($query) {
                       return $query->whereHas('amenities', function ($query) {
                           return $query->where('rns_id', 22)->where('description', '!=', 'No');
                       });
                   })
                   ->when($linens, function ($query) {
                       return $query->whereHas('amenities', function ($query) {
                           return $query->where('rns_id', 48)->where('description', '!=', 'No');
                       });
                   })
                   ->when($pets, function ($query) {
                       return $query->whereHas('amenities', function ($query) {
                           return $query->where('rns_id', 50)->where('description', '!=', 'No');
                       });
                   })
                   ->orderBy('name', 'asc')
                   ->paginate(18);

        $units->appends($request->all())->links();

        return $units;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Unit::with('amenities', 'images', 'availability', 'details', 'rates')->where('rns_id', $id)->first();
    }
}
