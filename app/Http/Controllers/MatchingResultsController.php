<?php

namespace App\Http\Controllers;

use App\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MatchingResultsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $checkIn  = isset($request->checkIn) && $request->checkIn !== '' ? Carbon::parse($request->checkIn) : null;
        $checkOut = isset($request->checkOut) && $request->checkOut !== '' ? Carbon::parse($request->checkOut) : null; 
        $location = $request->location ?? null;
        $type     = $request->type ?? null;

        return Unit::with('searchCriteria', 'details', 'rates', 'amenities')
                    ->with(['images' => function ($query) {
                        return $query->where('sort_order', 1);
                    }])
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
                   ->count();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
