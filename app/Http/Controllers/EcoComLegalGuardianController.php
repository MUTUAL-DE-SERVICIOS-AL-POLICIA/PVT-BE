<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EcoComLegalGuardian;
use Muserpol\Helpers\Util;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class EcoComLegalGuardianController extends Controller
{
    public function getEcoComLegalGuardian($id)
    {
        $eco_com = EconomicComplement::find($id);
        $legal_guardian = $eco_com->eco_com_legal_guardian;
        
        if ($legal_guardian) {
            $legal_guardian->phone_number = Util::parsePhone($legal_guardian->phone_number);
            $legal_guardian->cell_phone_number = Util::parsePhone($legal_guardian->cell_phone_number);
            return $legal_guardian;
        } else {
            return new EcoComLegalGuardian();
        }
    }
    public function update(Request $request)
    {
        try {
            $this->authorize('update', new EcoComLegalGuardian());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para editar la actualizar la información del apoderado'],
            ], 403);
        }
        $rules = [
            'identity_card' => 'required',
            'first_name' => 'required',
        ];
        if ($request->is_duedate_undefined == 'on') {
            $rules['is_duedate_undefined'] = 'required';
        } else {
            $rules['due_date'] = 'required';
        }
        try {
            $this->validate($request, $rules);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => $exception->errors(),
            ], 422);
        }
        $eco_com = EconomicComplement::find($request->eco_com_id);
        $legal_guardian = $eco_com->eco_com_legal_guardian;

        if (!$legal_guardian) {
            $legal_guardian = new EcoComLegalGuardian();
            $legal_guardian->economic_complement_id = $request->eco_com_id;
        }
        $legal_guardian->eco_com_legal_guardian_type_id = $request->eco_com_legal_guardian_type_id;
        $legal_guardian->city_identity_card_id = $request->city_identity_card_id;
        $legal_guardian->identity_card = $request->identity_card;
        $legal_guardian->last_name = $request->last_name;
        $legal_guardian->mothers_last_name = $request->mothers_last_name;
        $legal_guardian->first_name = $request->first_name;
        $legal_guardian->second_name = $request->second_name;
        $legal_guardian->surname_husband = $request->surname_husband;
        $legal_guardian->phone_number = implode(',', collect($request->phone_number)->pluck('value')->toArray());
        $legal_guardian->cell_phone_number = implode(',', collect($request->cell_phone_number)->pluck('value')->toArray());
        $legal_guardian->due_date = Util::verifyBarDate($request->due_date) ? Util::parseBarDate($request->due_date) : $request->due_date;
        $legal_guardian->is_duedate_undefined = $request->is_duedate_undefined == 'on';
        if ($request->is_duedate_undefined == 'on') {
            $legal_guardian->due_date = null;
        }
        $legal_guardian->number_authority = $request->number_authority;
        $legal_guardian->notary_of_public_faith = $request->notary_of_public_faith;
        $legal_guardian->notary = $request->notary;
        $legal_guardian->date_authority = Util::verifyBarDate($request->date_authority) ? Util::parseBarDate($request->date_authority) : $request->date_authority;
        $legal_guardian->gender = $request->gender;

        $legal_guardian->save();

        $legal_guardian->phone_number = Util::parsePhone($legal_guardian->phone_number);
        $legal_guardian->cell_phone_number = Util::parsePhone($legal_guardian->cell_phone_number);
        return $legal_guardian;
    }

    public function delete(Request $request)
    {
        try {
            $this->authorize('delete', new EcoComLegalGuardian());
        } catch (AuthorizationException $exception) {
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para eliminar el apoderado'],
            ], 403);
        }
        // try {
        //     $this->validate($request, [
        //         'observationTypeId' => 'required',
        //     ]);
        // } catch (ValidationException $exception) {
        //     return response()->json([
        //         'status' => 'error',
        //         'errors' => $exception->errors(),
        //     ], 422);
        // }
        $legal_guardian = EcoComLegalGuardian::find($request->id);
        if ($legal_guardian) {
            $legal_guardian->delete();
            return;
          }else{
            return response()->json(['errors' => ['No existe el apoderado']], 422);
        }
    }
}
