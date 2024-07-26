<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Helpers\Util;
use Muserpol\Models\Affiliate;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Muserpol\Models\EconomicComplement\EcoComRegulation;

class EcoComProcedureController extends Controller
{
    public function getProcedureActives(Request $request)
    {
        $eco_com_procedure_ids = Util::getEcoComCurrentProcedure();
        if ($eco_com_procedure_ids->count() == 0 ){
            return response()->json([], 204);
        }
        $affiliate = Affiliate::find($request->affiliate_id);
        if ($request->id) {
            $index = $eco_com_procedure_ids->search($request->id);
            if ($index === false) {
                // retornar activo;
                if ($affiliate->canCreateEcoComProcedure(end($eco_com_procedure_ids))) {
                    return EcoComProcedure::find($eco_com_procedure_ids->last());
                }
                if ($affiliate->canCreateEcoComProcedure($eco_com_procedure_ids->first())) {
                    return EcoComProcedure::find($eco_com_procedure_ids->first());
                }
                return response()->json([], 204);
            }
            $eco_com_procedure = EcoComProcedure::find($request->id);
            $next_eco_com_procedure = $eco_com_procedure->getNextProcedure();
            if ($next_eco_com_procedure) {
                if ($affiliate->canCreateEcoComProcedure($next_eco_com_procedure->id)) {
                    return $next_eco_com_procedure;
                } else {
                    return response()->json([], 204);
                }
            } else {
                return response()->json([], 204);
            }
        }
        if ($affiliate->canCreateEcoComProcedure($eco_com_procedure_ids->last())) {
            return EcoComProcedure::find($eco_com_procedure_ids->last());
        }
        if ($affiliate->canCreateEcoComProcedure($eco_com_procedure_ids->first())) {
            return EcoComProcedure::find($eco_com_procedure_ids->first());
        }
        return response()->json([], 204);
    }
    public function getProcedures()
    {
        try{
            $this->authorize('read', new EcoComProcedure());
        }catch(AuthorizationException $exception){
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para ver los procedimientos.'],
            ], 403);
        }
        $eco_com_procedures = EcoComProcedure::orderByDesc('year')->orderByDesc('semester')->get();
        foreach ($eco_com_procedures as $e) {
            $e->name = $e->fullName();
        }
        return $eco_com_procedures;
    }
    public function create(Request $request)
    {
        try{
            $this->authorize('create', new EcoComProcedure());
        }catch(AuthorizationException $exception){
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para crear.'],
            ], 403);
        }
        try {
            $this->validate($request, [
                'year' => 'required',
                'semester' => 'required',
                'rent_month' => 'required',
                'normal_start_date' => 'required',
                'normal_end_date' => 'required',
                'lagging_start_date' => 'required',
                'lagging_end_date' => 'required',
                'additional_start_date' => 'required',
                'additional_end_date' => 'required',
                'indicator' => 'required',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $eco_com_procedure = EcoComProcedure::whereYear('year', '=', $request->year)->where('semester', '=', $request->semester)->first();
        if ($eco_com_procedure) {
            return response()->json([
                'status' => 'error',
                'errors' => ['Ya existe.'],
            ], 422);
        }

        $eco_com_regulation = EcoComRegulation::where('is_enable', '=', true)->orderBy('created_at','desc')->first();
        if(!$eco_com_regulation){
            return response()->json([
                'status' => 'error',
                'errors' => ['No existe una regulación vigente.'],
            ], 422);
        }
        $eco_com_procedure =  new EcoComProcedure();
        $eco_com_procedure->user_id = auth()->user()->id;
        $eco_com_procedure->year = $request->year . "-01-01";
        $eco_com_procedure->semester = $request->semester;
        $eco_com_procedure->rent_month = $request->rent_month;
        $eco_com_procedure->normal_start_date = Util::verifyBarDate($request->normal_start_date) ? Util::parseBarDate($request->normal_start_date) : $request->normal_start_date;
        $eco_com_procedure->normal_end_date = Util::verifyBarDate($request->normal_end_date) ? Util::parseBarDate($request->normal_end_date) : $request->normal_end_date;
        $eco_com_procedure->lagging_start_date = Util::verifyBarDate($request->lagging_start_date) ? Util::parseBarDate($request->lagging_start_date) : $request->lagging_start_date;
        $eco_com_procedure->lagging_end_date = Util::verifyBarDate($request->lagging_end_date) ? Util::parseBarDate($request->lagging_end_date) : $request->lagging_end_date;
        $eco_com_procedure->additional_start_date = Util::verifyBarDate($request->additional_start_date) ? Util::parseBarDate($request->additional_start_date) : $request->additional_start_date;
        $eco_com_procedure->additional_end_date = Util::verifyBarDate($request->additional_end_date) ? Util::parseBarDate($request->additional_end_date) : $request->additional_end_date;
        $eco_com_procedure->indicator = $request->indicator;
        $eco_com_procedure->eco_com_regulation_id = $eco_com_regulation->id;
        $eco_com_procedure->save();
        return 'created';
    }
    public function update(Request $request)
    {
        try{
            $this->authorize('update', new EcoComProcedure());
        }catch(AuthorizationException $exception){
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para editar'],
            ], 403);
        }
        try {
            $this->validate($request, [
                'year' => 'required',
                'semester' => 'required',
                'rent_month' => 'required',
                'normal_start_date' => 'required',
                'normal_end_date' => 'required',
                'lagging_start_date' => 'required',
                'lagging_end_date' => 'required',
                'additional_start_date' => 'required',
                'additional_end_date' => 'required',
                'indicator' => 'required',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $eco_com_procedure = EcoComProcedure::find($request->id);
        if ($eco_com_procedure) {
            $eco_com_procedure->user_id = auth()->user()->id;
            $eco_com_procedure->year = $request->year;
            $eco_com_procedure->semester = $request->semester;
            $eco_com_procedure->rent_month = $request->rent_month;
            $eco_com_procedure->normal_start_date = Util::verifyBarDate($request->normal_start_date) ? Util::parseBarDate($request->normal_start_date) : $request->normal_start_date;
            $eco_com_procedure->normal_end_date = Util::verifyBarDate($request->normal_end_date) ? Util::parseBarDate($request->normal_end_date) : $request->normal_end_date;
            $eco_com_procedure->lagging_start_date = Util::verifyBarDate($request->lagging_start_date) ? Util::parseBarDate($request->lagging_start_date) : $request->lagging_start_date;
            $eco_com_procedure->lagging_end_date = Util::verifyBarDate($request->lagging_end_date) ? Util::parseBarDate($request->lagging_end_date) : $request->lagging_end_date;
            $eco_com_procedure->additional_start_date = Util::verifyBarDate($request->additional_start_date) ? Util::parseBarDate($request->additional_start_date) : $request->additional_start_date;
            $eco_com_procedure->additional_end_date = Util::verifyBarDate($request->additional_end_date) ? Util::parseBarDate($request->additional_end_date) : $request->additional_end_date;
            $eco_com_procedure->indicator = $request->indicator;
            $eco_com_procedure->save();
        } else {
            return response()->json(['errors' => ['No existe.']], 422);
        }
        return "updated";
    }
    public function delete(Request $request)
    {
        try {
            $this->authorize('delete', new EcoComProcedure());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para eliminar'],
            ], 403);
        }
        try {
            $this->validate($request, [
                'id' => 'required',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $eco_com_procedure = EcoComProcedure::find($request->id);
        if ($eco_com_procedure->economic_complements->count()) {
            return response()->json(['errors' => ['Existen Trámites con ese procedimiento.']], 422);
        }
        if ($eco_com_procedure) {
            $eco_com_procedure->delete();
        } else {
            return response()->json(['errors' => ['No existe.']], 422);
        }
    }
}
