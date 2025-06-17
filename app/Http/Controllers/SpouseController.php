<?php

namespace Muserpol\Http\Controllers;

use Muserpol\Models\Spouse;
use Muserpol\Models\City;
use Muserpol\Models\Affiliate;
use Illuminate\Http\Request;
use Log;
use Validator;
use Muserpol\Helpers\Util;
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
        $spouse = Affiliate::find($request->affiliate_id)->spouse->first();
        if (!$spouse) {
            $spouse = new Spouse();
        }
        $spouse->user_id = Auth::user()->id;
        $spouse->affiliate_id = $request->affiliate_id;
        $spouse->city_identity_card_id = $request->city_identity_card_id;
        $spouse->identity_card = $request->identity_card;
        $spouse->registration = $request->registration;
        $spouse->last_name = $request->last_name;
        $spouse->mothers_last_name = $request->mother_last_namel;
        $spouse->first_name = $request->first_name;
        $spouse->second_name = $request->second_name;
        $spouse->surname_husband = $request->surname_husband;
        $spouse->civil_status = $request->civil_status;
        $spouse->birth_date = Util::verifyBarDate($request->birth_date) ? Util::parseBarDate($request->birth_date) : $request->birth_date;
        $spouse->date_death = $request->date_death;
        $spouse->save();
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
    public function update(Request $request, $affiliate_id)
    {
        $rules = [
            'identity_card' => 'required|min:1',
            'first_name' => 'required|min:1',
            'last_name' => '',
            'mothers_last_name' => '',
            'birth_date' => 'required',
        ];
        $messages = [];
        if (!$request->last_name && !$request->mothers_last_name) {
            //only for flash message
            $rules['last_name'] .= 'required';
            $messages = [
                'last_name.required' => 'El campo Apellido Paterno o Materno es requerido.',
            ];
        }
        try {
            $validator = Validator::make($request->all(), $rules, $messages)->validate();
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => $exception->errors(),
            ], 403);
        }
        Util::updateCreateSpousePersonalInfo($affiliate_id, $request);
        $spouse = Affiliate::find($affiliate_id)->spouse->first();
        if (!$spouse) {
            $spouse = new Spouse();
        } else {
            $spouse->load([
                'city_identity_card:id,first_shortened',
                'city_birth:id,name',
            ]);
        }
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
    public function findSpouseOrAffiliateData($identityCard){
        $datos = Affiliate::select([
            'identity_card as cedula',
            'first_name',
            'second_name',
            'last_name',
            'mothers_last_name',
            'surname_husband',
            'birth_date',
            'city_birth_id',
            'civil_status',
            'date_death',
            'reason_death',
            'death_certificate_number'
        ])
        ->where('identity_card', $identityCard)
        ->first();
        if (!$datos) {
            $datos = Spouse::select([
                'identity_card as cedula',
                'first_name',
                'second_name',
                'last_name',
                'mothers_last_name',
                'surname_husband',
                'birth_date',
                'city_birth_id',
                'civil_status',
                'date_death',
                'reason_death',
                'death_certificate_number'
            ])
            ->where('identity_card', $identityCard)
            ->first();
        }
        return response()->json(['spouses' => $datos]);
        }

    public function getRecord($affiliate_id)
    {
        $affiliate = Affiliate::find($affiliate_id);
        if (!$affiliate) {
            return response()->json(['error' => 'Affiliate not found'], 404);
        }
        $spouse = $affiliate->spouse->first();
        if (!$spouse) {
            return response()->json(['error' => 'Spouse not found'], 404);
        }
        $spouse_records = $spouse->records()->orderByDesc('created_at')->get();
        return response()->json(['spouse_records' => $spouse_records]);
    }

}
