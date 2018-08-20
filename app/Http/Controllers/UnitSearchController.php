<?php

namespace App\Http\Controllers;

use App\Unit;
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
        $location = $request->location ?? null;
        $type     = $request->type ?? null;

        return Unit::with('amenities', 'images', 'availability', 'details', 'rates')
                    ->when($type, function ($query) use ($type) {
                        return $query->where('type', $type);
                    })->when($location, function ($query) use ($location) {
                        return $query->where('location', $location);
                    })->get();
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
