<?php

namespace Muserpol\Http\Controllers;

use Muserpol\Models\Spouse;
use Muserpol\Models\City;
use Muserpol\Models\Affiliate;
use Illuminate\Http\Request;

class SpouseController extends Controller
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
    public function update(Request $request)
    {
        $spouse = Spouse::where('id', '=', $request.id)->first();
       
        // $this->authorize('update', $spouse);

        $spouse->identity_card = $request->identity_card;
        $spouse->registration = $request->registration;
        $spouse->first_name = $request->first_name;
        $spouse->second_name = $request->second_name;
        $spouse->last_name = $request->last_name;
        $spouse->mothers_last_name = $request->mothers_last_name;
        $spouse->surname_husband = $request->surname_husband;
        $spouse->civil_status = $request->civil_status;
        $spouse->birth_date = $request->birth_date;
        $spouse->city_birth_id = $request->city_birth_id;
        $spouse->city_identity_card_id = $request->city_identity_card_id;

        $spouse->save();

        $datos = array('spouse' => $spouse, 'city_birth' => $spouse->city_birth, 'city_identity_card' => $spouse->city_identity_card);
        return $datos;
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
