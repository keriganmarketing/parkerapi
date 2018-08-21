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
        $name     = $request->name ?? null;
        $checkIn  = isset($request->checkIn) && $request->checkIn !== '' ? Carbon::parse($request->checkIn) : null;
        $checkOut = isset($request->checkOut) && $request->checkOut !== '' ? Carbon::parse($request->checkOut) : null; 
        $location = $request->location ?? null;
        $type     = $request->type ?? null;

        $units = Unit::with('details', 'rates')
                    ->with(['images' => function ($query) {
                        return $query->where('sort_order', 1);
                    }])
                   ->when($name, function ($query) use ($name) {
                        return $query->where('name', 'like', $name);
                   })
                   ->when($checkIn, function ($query) use ($checkIn) {
                        return $query->whereHas('availability', function ($query) use ($checkIn){
                            return $query->where('arrival_date', '>=', $checkIn);
                        });
                   })
                   ->when($checkOut, function ($query) use ($checkOut) {
                        return $query->whereHas('availability', function ($query) use ($checkOut){
                            return $query->where('arrival_date', '<=', $checkOut);
                        });
                   })
                   ->when($location, function ($query) use ($location){
                       return $query->where('location', $location);
                   })
                   ->when($type, function ($query) use ($type){
                       return $query->where('type', $type);
                   })
                   ->paginate(36);

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
        return Unit::with('amenities', 'images', 'availability', 'details', 'rates')->find(id);
    }
}
