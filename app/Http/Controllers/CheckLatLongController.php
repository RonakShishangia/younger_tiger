<?php

namespace App\Http\Controllers;

use App\CheckLatLong;
use Illuminate\Http\Request;

class CheckLatLongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        // $products = Product::with(['owner' => function($query) use ($latitude, $longitude, $radius) {
        //     $query->selectRaw('( 6371 * acos( cos( radians(?) ) *
        //                              cos( radians( latitude ) )
        //                              * cos( radians( longitude ) - radians(?)
        //                              ) + sin( radians(?) ) *
        //                              sin( radians( latitude ) ) )
        //                            ) AS distance', [$latitude, $longitude])
        //     ->havingRaw("distance < ?", [$radius]);
        //   }])->get();
        $latitude =  $request->fLat;
        $longitude = $request->fLon;
      
        $checkLatLongs = CheckLatLong::all();
        dd($checkLatLongs);
        $checkLatLongs = CheckLatLong::select('id','name', 'ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $request->fLat ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( $request->Lat )) * COS( RADIANS( `longitude` ) - RADIANS( $request->fLon )) ) * 6380 AS `distance`')->WHERE('ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( $request->fLat ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( $request->fLat )) * COS( RADIANS( `longitude` ) - RADIANS( $request->fLon )) ) * 6380 < 10')->orderBy(`distance`,'DESC')->get();
        $count = $checkLatLongs->count();
        dd($count);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CheckLatLong  $checkLatLong
     * @return \Illuminate\Http\Response
     */
    public function show(CheckLatLong $checkLatLong)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CheckLatLong  $checkLatLong
     * @return \Illuminate\Http\Response
     */
    public function edit(CheckLatLong $checkLatLong)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CheckLatLong  $checkLatLong
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CheckLatLong $checkLatLong)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CheckLatLong  $checkLatLong
     * @return \Illuminate\Http\Response
     */
    public function destroy(CheckLatLong $checkLatLong)
    {
        //
    }

   
    public function haversineGreatCircleDistance($request){
        /**
         * Calculates the great-circle distance between two points, with
         * the Haversine formula.
         * @param float $latitudeFrom Latitude of start point in [deg decimal]
         * @param float $longitudeFrom Longitude of start point in [deg decimal]
         * @param float $latitudeTo Latitude of target point in [deg decimal]
         * @param float $longitudeTo Longitude of target point in [deg decimal]
         * @param float $earthRadius Mean earth radius in [m]
         * @return float Distance between points in [m] (same as earthRadius)
         */
        $latitudeFrom =  $request->latitudeFrom;
        $longitudeFrom =  $request->longitudeFrom;
        $latitudeTo = $request->latitudeTo;
        $longitudeTo = $request->longitudeTo;
        $earthRadius = 6371000;
        
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
    
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
    
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +  cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $data = $angle * $earthRadius; 
        return $data; 
    }

    public function get_distance_between_points(Request $request) {
        $meters = $this->haversineGreatCircleDistance($request);
        $kilometers = $meters / 1000;
        $miles = $meters / 1609.34;
        $yards = $miles * 1760;
        $feet = $miles * 5280;
        return compact('miles','feet','yards','kilometers','meters');
    }
}