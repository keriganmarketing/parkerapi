<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Availability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $availabilities = Availability::all();

        foreach ($availabilities as $availability) {
            $availability->start = Carbon::parse($availability->arrival_date)->format('Y/n/j');
            $availability->end   = Carbon::parse($availability->departure_date)->format('Y/n/j');
        }

        return $availabilities;
    }
}
