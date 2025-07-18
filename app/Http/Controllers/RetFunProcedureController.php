<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\RetirementFund\RetFunProcedure;
use Illuminate\Support\Facades\DB;

class RetFunProcedureController extends Controller
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
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'limit_average' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $actualProcedure = RetFunProcedure::active_procedure();
            
            // Todos los datos se duplican menos start_date
            $procedure = $actualProcedure->replicate();
            $procedure->start_date = $request->start_date;
            $procedure->limit_average = $request->limit_average;
            $procedure->save();
        });

        return redirect()->back()->with('success', 'Guardado correctamente.');
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
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'limit_average' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($id, $request) {
            $procedure = RetFunProcedure::findOrFail($id);
            $procedure->start_date = $request->start_date;
            $procedure->save();
        });

        return redirect()->back()->with('success', 'Editado correctamente.');
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
