<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\RetirementFund\RetFunProcedure;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $year = Carbon::parse($value)->year;
                    $exists = RetFunProcedure::whereYear('start_date', $year)->exists();
                    if ($exists) {
                        $fail('Ya existe un registro para el año ' . $year . '.');
                    }
                },
            ],
            'limit_average' => 'required|numeric|min:1',
            'contributions_limit' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $actualProcedure = RetFunProcedure::active_procedure();

            // Todos los datos se duplican menos start_date y limit_average
            $procedure = $actualProcedure->replicate();
            $procedure->start_date = $request->start_date;
            $procedure->limit_average = $request->limit_average;
            $procedure->contributions_limit = $request->contributions_limit;
            $procedure->save();
        });

        return response()->json(['message' => 'Creado correctamente.'], 200);
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
        $procedure = RetFunProcedure::findOrFail($id);

        $request->validate([
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) use ($procedure) {
                    $newYear = Carbon::parse($value)->year;
                    $originalYear = Carbon::parse($procedure->start_date)->year;
                    if ($newYear !== $originalYear) {
                        $fail('No se puede cambiar el año del registro. Debe permanecer en el año ' . $originalYear . '.');
                    }
                },
            ],
            'limit_average' => 'required|numeric|min:1',
            'contributions_limit' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($procedure, $request) {
            $procedure->start_date = $request->start_date;
            $procedure->limit_average = $request->limit_average;
            $procedure->contributions_limit = $request->contributions_limit;
            $procedure->save();
        });

        return response()->json(['message' => 'Editado correctamente.'], 200);
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
