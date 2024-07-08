<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\ObservationType;
use Carbon\Carbon;
use Auth;
use DB;
use Muserpol\Helpers\Util;
use Illuminate\Auth\Access\AuthorizationException;
use Muserpol\Models\Affiliate;
use Muserpol\Models\AffiliateRecord;
use Muserpol\Models\EconomicComplement\EconomicComplement;

class AffiliateObservationController extends Controller
{
    public function getObservations($affiliate_id)
    {
        try {
            $this->authorize('read', new ObservationType());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para ver las observaciones'],
            ], 403);
        }
        $affiliate = Affiliate::find($affiliate_id);
        return $affiliate->observations;
    }
    public function getDeleteObservations($affiliate_id)
    {
        try {
            $this->authorize('read', new ObservationType());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para ver las observaciones'],
            ], 403);
        }
        $observations = collect(DB::table('observables')
            ->where('observable_id', $affiliate_id)
            ->where('observable_type', 'like', 'affiliates')
            ->whereNotNull('deleted_at')
            ->leftJoin('observation_types', 'observables.observation_type_id', '=', 'observation_types.id')
            ->select(['observables.*', 'observation_types.name', 'observation_types.type'])
            ->get());
        return $observations;
    }
    public function create(Request $request)
    {
        try {
            $this->authorize('create', new ObservationType());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para crear observaciones'],
            ], 403);
        }
        try {
            $this->validate($request, [
                'message' => 'required|min:10|max:250',
                'observationTypeId' => 'required',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $affiliate = Affiliate::find($request->affiliateId);
        $observation = ObservationType::find($request->observationTypeId);
        // ?? Puede tener 2 observaciones del mismo tipo??
        $affiliate->observations()->save($observation, [
            'user_id' => Auth::user()->id,
            'date' => Carbon::now(),
            'message' => $request->message,
            'enabled' => $request->enabled
        ]);
        $record = new AffiliateRecord();
        $record->user_id = Auth::user()->id;
        $record->affiliate_id = $affiliate->id;
        $record->message = "El usuario " . Auth::user()->username  . " creó la observación " . $observation->name . ".";
        $record->save();
        return 'created';
    }
    public function update(Request $request)
    {
        try {
            $this->authorize('update', new ObservationType());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para editar observaciones'],
            ], 403);
        }
        try {
            $this->validate($request, [
                'message' => 'required|min:10|max:250',
                'editObservationTypeId' => 'required',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $affiliate = Affiliate::find($request->affiliateId);
        $observation = ObservationType::find($request->editObservationTypeId);
        if ($affiliate->observations->contains($observation->id)) {
            $old_observation = $affiliate->observations()->find($observation->id);
            $affiliate->observations()->updateExistingPivot($observation->id, [
                'user_id' => Auth::user()->id,
                'date' => Carbon::now(),
                'message' => $request->message,
                'enabled' => $request->enabled
            ]);
            $record = new AffiliateRecord();
            $record->user_id = Auth::user()->id;
            $record->affiliate_id = $affiliate->id;
            $message = "El usuario " . Auth::user()->username  . " modifico la observación " . $observation->name . " :";
            if ($old_observation->pivot->message != $request->message) {
                $message = $message . ' Mensaje de - ' . $old_observation->pivot->message . ' - a - ' . $request->message . ', ';
            }
            if ($old_observation->pivot->enabled != $request->enabled) {
                $message = $message . ' de ' . Util::getEnabledLabel($old_observation->pivot->enabled) . ' a ' . Util::getEnabledLabel($request->enabled) . ', ';
            }
            $record->message = $message . ".";
            $record->save();
        } else {
            return response()->json(['errors' => ['El Trámite no tiene esa observación']], 422);
        }
        return "updated";
    }
    public function delete(Request $request)
    {
        try {
            $this->authorize('delete', new ObservationType());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para eliminar observaciones'],
            ], 403);
        }
        try {
            $this->validate($request, [
                'observationTypeId' => 'required',
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $affiliate = Affiliate::find($request->affiliateId);
        $observation = ObservationType::find($request->observationTypeId);
        if ($affiliate->observations->contains($observation->id)) {
            if($observation->id == 31){//pago a futuro
                $eco_com_process = DB::table('observables')->select('observables.observable_id')->join('economic_complements','observables.observable_id','economic_complements.id')->where('observable_type', 'economic_complements')->where('economic_complements.affiliate_id', $affiliate->id)->where('economic_complements.eco_com_state_id',16)->whereNull('observables.deleted_at')->whereNull('economic_complements.deleted_at')->where('observables.observation_type_id','=',31)->distinct()->get();
                if($eco_com_process->count() == 0){
                     $affiliate->observations()->updateExistingPivot($observation->id, [
                         'deleted_at' => Carbon::now(),
                     ]);
                     $record = new AffiliateRecord();
                     $record->user_id = Auth::user()->id;
                     $record->affiliate_id = $affiliate->id;
                     $record->message = "El usuario " . Auth::user()->username  . " eliminó la observación " . $observation->name . ".";
                     $record->save();
                } else {
                      return response()->json(['errors' => ['Para eliminar la observación, primero debe eliminar la observación del tramite en proceso']], 422);
                }
            }else{
                $affiliate->observations()->updateExistingPivot($observation->id, [
                    'deleted_at' => Carbon::now(),
                ]);
                $record = new AffiliateRecord();
                $record->user_id = Auth::user()->id;
                $record->affiliate_id = $affiliate->id;
                $record->message = "El usuario " . Auth::user()->username  . " eliminó la observación " . $observation->name . ".";
                $record->save();
            }
        } else {
            return response()->json(['errors' => ['El Trámite no tiene esa observación']], 422);
        }
    }
}
