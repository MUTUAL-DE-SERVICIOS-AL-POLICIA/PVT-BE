<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\RetirementFund\RetFunProcedure;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Muserpol\Models\Hierarchy;

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
            'contributions_limit' => 'required|numeric|min:1',
        ]);

        $actualProcedure = RetFunProcedure::active_procedure();

        $hierarchies_sync_data = [];
        foreach ($request->hierarchies as $hierarchiesKey => $hierarchiesValue) {
            $hierarchies_sync_data[$hierarchiesKey] = [
                'apply_contributions_limit' => $hierarchiesValue['apply_contributions_limit'],
                'average_salary_limit' => $hierarchiesValue['average_salary_limit']
            ];
        }
        
        $modalities_sync_data = [];
        foreach ($request->procedureType as $procedureTypeKey => $procedureTypeValues) {
            foreach ($procedureTypeValues['modalitiesIds'] as $modalityId) {
                $modalities_sync_data[$modalityId] = [
                    'annual_percentage_yield' => $procedureTypeValues['percentageYield'],
                ];
            }
        }

        DB::transaction(function () use ($request, $actualProcedure, $hierarchies_sync_data, $modalities_sync_data) {
            // Todos los datos se duplican menos start_date y limit_average
            $procedure = $actualProcedure->replicate();
            $procedure->start_date = $request->start_date;
            $procedure->contributions_limit = $request->contributions_limit;
            $procedure->save();
            $procedure->hierarchies()->sync($hierarchies_sync_data);
            $procedure->procedure_modalities()->sync($modalities_sync_data);
        });

        return response()->json(['message' => 'Creado correctamente.'], 201);
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
            'contributions_limit' => 'required|numeric|min:1',
        ]);

        $hierarchies_sync_data = [];
        foreach ($request->hierarchies as $hierarchiesKey => $hierarchiesValue) {
            $hierarchies_sync_data[$hierarchiesKey] = [
                'apply_contributions_limit' => $hierarchiesValue['apply_contributions_limit'],
                'average_salary_limit' => $hierarchiesValue['average_salary_limit']
            ];
        }

        $modalities_sync_data = [];
        foreach ($request->procedureType as $procedureTypeKey => $procedureTypeValues) {
            foreach ($procedureTypeValues['modalitiesIds'] as $modalityId) {
                $modalities_sync_data[$modalityId] = [
                    'annual_percentage_yield' => $procedureTypeValues['percentageYield'],
                ];
            }
        }

        DB::transaction(function () use ($request, $procedure, $hierarchies_sync_data, $modalities_sync_data) {
            $procedure->update([
                'start_date' => $request->start_date,
                'contributions_limit' => $request->contributions_limit,
            ]);

            $procedure->hierarchies()->sync($hierarchies_sync_data);
            $procedure->procedure_modalities()->sync($modalities_sync_data);
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
