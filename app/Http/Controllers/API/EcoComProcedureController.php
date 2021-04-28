<?php

namespace Muserpol\Http\Controllers\API;

use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Http\Resources\EcoComProcedureResource;
use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;

class EcoComProcedureController extends Controller
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
     * @param  \Muserpol\Models\EconomicComplement\EcoComProcedure  $ecoComProcedure
     * @return \Illuminate\Http\Response
     */
    public function show(EcoComProcedure $ecoComProcedure)
    {
        return new EcoComProcedureResource($ecoComProcedure);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\Models\EconomicComplement\EcoComProcedure  $ecoComProcedure
     * @return \Illuminate\Http\Response
     */
    public function edit(EcoComProcedure $ecoComProcedure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\Models\EconomicComplement\EcoComProcedure  $ecoComProcedure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EcoComProcedure $ecoComProcedure)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\Models\EconomicComplement\EcoComProcedure  $ecoComProcedure
     * @return \Illuminate\Http\Response
     */
    public function destroy(EcoComProcedure $ecoComProcedure)
    {
        //
    }
}
