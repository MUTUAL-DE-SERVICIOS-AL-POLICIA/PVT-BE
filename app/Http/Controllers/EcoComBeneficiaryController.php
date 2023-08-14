<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;
use Muserpol\Models\Address;
use Muserpol\Helpers\Util;
use Muserpol\Helpers\ID;
use Muserpol\Models\Spouse;
use Auth;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\Carbon;
use Muserpol\Models\City;

class EcoComBeneficiaryController extends Controller
{
    public function getEcoComBeneficiary($id)
    {
        $eco_com = EconomicComplement::find($id);
        $beneficiary = $eco_com->eco_com_beneficiary;
        if($beneficiary){
            $beneficiary->phone_number = Util::parsePhone($beneficiary->phone_number);
            $beneficiary->cell_phone_number = Util::parsePhone($beneficiary->cell_phone_number);
            $beneficiary->address;
            return $beneficiary;
        }else{
            $beneficiary = new EcoComBeneficiary();
            $beneficiary->address;
            return $beneficiary;
            // return new EcoComBeneficiary();
        }
    }
    public function update(Request $request)
    {
        try{
            $this->authorize('update', new EcoComBeneficiary());
        }catch(AuthorizationException $exception){
            return response()->json([
                'status' => 'error',
                'errors' => ['No tiene permisos para editar la actualizar la información del apoderado'],
            ], 403);
        }
        $rules = [
            'identity_card' => 'required',
            'first_name' => 'required',
        ];
        if($request->is_duedate_undefined == 'on'){
            $rules['is_duedate_undefined'] = 'required';
        }else{
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
        $beneficiary = $eco_com->eco_com_beneficiary;
        if (!$beneficiary) {
            $beneficiary = new EcoComBeneficiary();
            $beneficiary->economic_complement_id = $eco_com->id;
        }
        $beneficiary->city_identity_card_id = $request->city_identity_card_id;
        $beneficiary->identity_card = mb_strtoupper(trim($request->identity_card));
        $beneficiary->last_name = mb_strtoupper(trim($request->last_name));
        $beneficiary->mothers_last_name = mb_strtoupper(trim($request->mothers_last_name));
        $beneficiary->first_name = mb_strtoupper(trim($request->first_name));
        $beneficiary->second_name = mb_strtoupper(trim($request->second_name));
        $beneficiary->surname_husband = mb_strtoupper(trim($request->surname_husband));
        $beneficiary->birth_date = Util::verifyBarDate($request->birth_date) ? Util::parseBarDate($request->birth_date) : $request->birth_date;
        $beneficiary->gender = $request->gender;
        $beneficiary->nua = $request->nua;
        $beneficiary->phone_number = implode(',',collect($request->phone_number)->pluck('value')->toArray());
        $beneficiary->cell_phone_number = implode(',',collect($request->cell_phone_number)->pluck('value')->toArray());
        $beneficiary->civil_status = $request->civil_status;
        $beneficiary->city_birth_id = $request->city_birth_id;
        $beneficiary->due_date = Util::verifyBarDate($request->due_date) ? Util::parseBarDate($request->due_date) : $request->due_date;
        $beneficiary->is_duedate_undefined = $request->is_duedate_undefined == 'on';
        if ($request->is_duedate_undefined == 'on') {
            $beneficiary->due_date = null;
        }
        $beneficiary->save();

        $eco_com = $beneficiary->economic_complement;
        $affiliate = $eco_com->affiliate;
        //  * Update or create address
        if ($beneficiary->address->count() > 0) {
            $address_id = $beneficiary->address()->first()->id;
            $address = Address::find($address_id);
            if ($request->address['zone'] || $request->address['street'] || $request->address['number_address']) {
                $message = "";
                if ($address->city_address_id != $request->address['city_address_id']) {
                    $message = $message . " Ciudad de ".optional($address->city)->name ." a ".optional(City::find($request->address['city_address_id']))->name;
                }
                if ($address->zone != $request->address['zone']) {
                    $message = $message . " Zona de ".$address->zone." a ".$request->address['zone'];
                }
                if ($address->street != $request->address['street']) {
                    $message = $message . " Calle de ".$address->street." a ".$request->address['street'];
                }
                if ($address->number_address != $request->address['number_address']) {
                    $message = $message . " Nro de ".$address->number_address." a ".$request->address['number_address'];
                }
                $address->city_address_id = $request->address['city_address_id'];
                $address->zone = $request->address['zone'];
                $address->street = $request->address['street'];
                $address->number_address = $request->address['number_address'];
                $address->save();
                if ($eco_com->isOldAge()) {
                    if (!$affiliate->address->contains($address->id)) {
                        $affiliate->address()->save($address);
                    }
                }
                if ($message != "") {
                    $eco_com->procedure_records()->create([
                        'user_id' => auth()->user()->id,
                        'record_type_id' => 14,
                        'wf_state_id' => Util::getRol()->wf_states->first()->id,
                        'date' => Carbon::now(),
                        'message' => "Edito la dirección: ". $message,
                    ]);
                }
            } else {
                if ($eco_com->isOldAge()) {
                    $affiliate->address()->detach($address->id);
                }
                $beneficiary->address()->detach($address->id);
                $eco_com->procedure_records()->create([
                    'user_id' => auth()->user()->id,
                    'record_type_id' => 14,
                    'wf_state_id' => Util::getRol()->wf_states->first()->id,
                    'date' => Carbon::now(),
                    'message' => "Elimino la dirección Ciudad: " .$address->city->name ." Zona: ".$address->zone. " Calle: ".$address->street. " Nro: ".$address->number_address.".",
                ]);
                $address->delete();
            }
        } else {
            if ($request->address) {
                $address = new Address();
                $address->city_address_id = $request->address['city_address_id'] ?? null;
                $address->zone = $request->address['zone'] ?? null;
                $address->street = $request->address['street'] ?? null;
                $address->number_address = $request->address['number_address'] ?? null;
                $address->save();
                $beneficiary->address()->save($address);
                if ($eco_com->isOldAge()) {
                    $affiliate->address()->save($address);
                }
                $eco_com->procedure_records()->create([
                    'user_id' => auth()->user()->id,
                    'record_type_id' => 14,
                    'wf_state_id' => Util::getRol()->wf_states->first()->id,
                    'date' => Carbon::now(),
                    'message' => "Registro un nueva dirección."
                ]);
            }
        }


        // /**
        //  ** observacion mayor de 25 en orfandad
        //  */
        // if ($request->modality_id == ID::ecoCom()->orphanhood && $eco_com_beneficiary->birth_date) {
        //     $beneficiary_years = intval(explode(' ', Util::calculateAge($eco_com_beneficiary->birth_date, null)[0]));
        //     if ($beneficiary_years > 25) {
        //         /**
        //          * !! TODO agregar una observacion o algo
        //          */
        //     }
        // }

        /**
         ** update affiliate and spouse
         */
        switch ($eco_com->eco_com_modality->procedure_modality_id) {
                // vejez update affiliate
            case ID::ecoCom()->old_age:
                $affiliate->city_identity_card_id = $request->city_identity_card_id;
                $affiliate->identity_card = $request->identity_card;
                $affiliate->last_name = $request->last_name;
                $affiliate->mothers_last_name = $request->mothers_last_name;
                $affiliate->first_name = $request->first_name;
                $affiliate->second_name = $request->second_name;
                $affiliate->surname_husband = $request->surname_husband ?? null;
                $affiliate->birth_date = Util::verifyBarDate($request->birth_date) ? Util::parseBarDate($request->birth_date) : $request->birth_date;
                $affiliate->nua = $request->nua;
                $affiliate->gender = $request->gender;
                $affiliate->civil_status = $request->civil_status;
                $affiliate->phone_number = implode(',',collect($request->phone_number)->pluck('value')->toArray());
                $affiliate->cell_phone_number = implode(',',collect($request->cell_phone_number)->pluck('value')->toArray());
                $affiliate->city_birth_id = $request->city_birth_id;
                $affiliate->due_date = Util::verifyBarDate($request->due_date) ? Util::parseBarDate($request->due_date) : $request->due_date;
                $affiliate->is_duedate_undefined = $request->is_duedate_undefined == 'on';
                if ($request->is_duedate_undefined == 'on') {
                    $affiliate->due_date = null;
                }
                $affiliate->save();
                break;
                // viudedad update or create spouse
            case ID::ecoCom()->widowhood:
                $spouse = Spouse::where('affiliate_id', $affiliate->id)->first();
                if (!$spouse) {
                    $spouse = new Spouse();
                }
                $spouse->user_id = Auth::user()->id;
                $spouse->affiliate_id = $affiliate->id;
                $spouse->city_identity_card_id = $request->city_identity_card_id;
                $spouse->identity_card = $request->identity_card;
                $spouse->registration = "";
                $spouse->last_name = $request->last_name;
                $spouse->mothers_last_name = $request->mothers_last_name;
                $spouse->first_name = $request->first_name;
                $spouse->second_name = $request->second_name;
                $spouse->surname_husband = $request->surname_husband ?? null;
                $spouse->civil_status = $request->civil_status;
                $spouse->birth_date = Util::verifyBarDate($request->birth_date) ? Util::parseBarDate($request->birth_date) : $request->birth_date;
                $spouse->city_birth_id = $request->city_birth_id;
                // $spouse->gender = $request->gender;
                // $spouse-> = trim(implode(",", $request->phone_number));
                // $spouse-> = trim(implode(",", $request->cell_phone_number));
                $spouse->due_date = Util::verifyBarDate($request->due_date) ? Util::parseBarDate($request->due_date) : $request->due_date;
                $spouse->is_duedate_undefined = $request->is_duedate_undefined == 'on';
                if ($request->is_duedate_undefined == 'on') {
                    $spouse->due_date = null;
                }
                $spouse->save();
                break;
            default:

                break;
        }
        $beneficiary = $beneficiary->fresh();
        $beneficiary->address;
        $beneficiary->phone_number = Util::parsePhone($beneficiary->phone_number);
        $beneficiary->cell_phone_number = Util::parsePhone($beneficiary->cell_phone_number);
        return $beneficiary;

    }
}
