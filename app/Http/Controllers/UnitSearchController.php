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
                   ->when($name, function ($query) use ($name) {
                        return $query->where('name', 'like', $name);
                   })
                   ->when($checkIn, function ($query) use ($checkIn) {
                        return $query->whereDoesntHave('availability', function ($query) use ($checkIn){
                            return $query->whereDate('arrival_date', '>=', $checkIn)->orWhereDate('departure_date', '<=', $checkIn);
                        });
                   })
                   ->when($checkOut, function ($query) use ($checkOut) {
                        return $query->whereDoesntHave('availability', function ($query) use ($checkOut){
                            return $query->whereDate('arrival_date', '>=', $checkOut)->orWhereDate('departure_date', '<=', $checkOut);
                        });
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
                   ->when($pool, function ($query) {
                       return $query->whereHas('amenities', function ($query) {
                           return $query->where('rns_id', 45)->where('description', '!=', 'No');
                       });
                   })
                   ->when($internet, function ($query) {
                       return $query->whereHas('amenities', function ($query) {
                           return $query->where('rns_id', 22)->where('description', '!=', 'No');
                       });
                   })
                   ->when($linens, function ($query) {
                       return $query->whereHas('amenities', function ($query) {
                           return $query->where('rns_id', 14)->where('description', '!=', 'No');
                       });
                   })
                   ->when($pets, function ($query) {
                       return $query->whereHas('amenities', function ($query) {
                           return $query->where('rns_id', 50)->where('description', '!=', 'No');
                       });
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
        return Unit::with('amenities', 'images', 'availability', 'details', 'rates')->where('rns_id', $id)->first();
    }
}
