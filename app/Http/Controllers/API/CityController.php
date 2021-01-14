<?php

namespace Muserpol\Http\Controllers\API;

use Muserpol\Models\City;
use Muserpol\Http\Controllers\Controller;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'error' => false,
            'message' => 'Lista de ciudades',
            'data' => [
                'cities' => City::where('phone_prefix', '>', 0)->select('id', 'name', 'latitude', 'longitude', 'company_address', 'phone_prefix', 'company_phones', 'company_cellphones')->get()
            ]
        ], 200);
    }
}
