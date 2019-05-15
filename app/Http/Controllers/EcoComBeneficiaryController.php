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

class EcoComBeneficiaryController extends Controller
{
    public function getEcoComBeneficiary($id)
    {
        $eco_com = EconomicComplement::find($id);
        $beneficiary = $eco_com->eco_com_beneficiary;

        if($beneficiary){
            $beneficiary->phone_number = Util::parsePhone($beneficiary->phone_number);
            $beneficiary->cell_phone_number = Util::parsePhone($beneficiary->cell_phone_number);
            return $beneficiary;
        }else{
            return new EcoComBeneficiary();
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
            'city_identity_card_id' => 'required',
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
        $beneficiary->is_duedate_undefined = $request->beneficiary_is_duedate_undefined == 'on';
        if ($request->beneficiary_is_duedate_undefined == 'on') {
            $beneficiary->due_date = null;
        }
        $beneficiary->save();

        $eco_com = $beneficiary->economic_complement;
        $affiliate = $eco_com->affiliate;
        // return $beneficiary;
        // /**
        //  * Update or create address
        //  */
        // if (sizeOf($beneficiary->address) > 0) {
        //     $address_id = $beneficiary->address()->first()->id;
        //     $address = Address::find($address_id);
        //     if ($request->address[0]['zone'] || $request->address[0]['street'] || $request->address[0]['number_address']) {
        //         $address->city_address_id = $request->address[0]['city_address_id'] ?? 1;
        //         $address->zone = $request->address[0]['zone'];
        //         $address->street = $request->address[0]['street'];
        //         $address->number_address = $request->address[0]['number_address'];
        //         $address->save();
        //         if ($eco_com->procedure_modality_id == 24) {
        //             $update_affiliate = $eco_com->affiliate;
        //             if ($update_affiliate->address->contains($address->id)) { } else {
        //                 $update_affiliate->address()->save($address);
        //             }
        //         }
        //     } else {
        //         if ($eco_com->procedure_modality_id == 24) {
        //             $update_affiliate = $eco_com->affiliate;
        //             $update_affiliate->address()->detach($address->id);
        //         }
        //         $beneficiary->address()->detach($address->id);
        //         $address->delete();
        //     }
        // } else {
        //     if (sizeOf($request->address) > 0) {
        //         $address = new Address();
        //         $address->city_address_id = $request->address[0]['city_address_id'] ?? 1;
        //         $address->zone = $request->address[0]['zone'];
        //         $address->street = $request->address[0]['street'];
        //         $address->number_address = $request->address[0]['number_address'];
        //         $address->save();
        //         $beneficiary->address()->save($address);
        //         if ($eco_com->procedure_modality_id == 24) {
        //             $update_affiliate = Affiliate::find($eco_com->affiliate_id);
        //             $update_affiliate->address()->save($address);
        //         }
        //     }
        // }


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
        // /**
        //  ** Update or create address
        //  */
        // if (sizeOf($eco_com_beneficiary->address) > 0) {
        //     $address_id = $eco_com_beneficiary->address()->first()->id;
        //     $address = Address::find($address_id);
        //     if ($request->beneficiary_zone || $request->beneficiary_street || $request->beneficiary_number_address) {
        //         $address->city_address_id = $request->beneficiary_city_address_id ?? 1;
        //         $address->zone = $request->beneficiary_zone;
        //         $address->street = $request->beneficiary_street;
        //         $address->number_address = $request->beneficiary_number_address;
        //         $address->save();
        //         if ($economic_complement->procedure_modality_id == ID::ecoCom()->old_age) {
        //             $update_affiliate = Affiliate::find($economic_complement->affiliate_id);
        //             if ($update_affiliate->address->contains($address->id)) { } else {
        //                 $update_affiliate->address()->save($address);
        //             }
        //         }
        //     } else {
        //         if ($economic_complement->procedure_modality_id == ID::ecoCom()->old_age) {
        //             $update_affiliate = Affiliate::find($economic_complement->affiliate_id);
        //             $update_affiliate->address()->detach($address->id);
        //         }
        //         $eco_com_beneficiary->address()->detach($address->id);
        //         $address->delete();
        //     }
        // } else {
        //     if ($request->beneficiary_city_address_id) {
        //         $address = new Address();
        //         $address->city_address_id = $request->beneficiary_city_address_id ?? 1;
        //         $address->zone = $request->beneficiary_zone;
        //         $address->street = $request->beneficiary_street;
        //         $address->number_address = $request->beneficiary_number_address;
        //         $address->save();
        //         $eco_com_beneficiary->address()->save($address);
        //         if ($economic_complement->procedure_modality_id == ID::ecoCom()->old_age) {
        //             $update_affiliate = Affiliate::find($economic_complement->affiliate_id);
        //             $update_affiliate->address()->save($address);
        //         }
        //     }
        // }
        // $eco_com_beneficiary->save();

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
        $beneficiary->phone_number = Util::parsePhone($beneficiary->phone_number);
        $beneficiary->cell_phone_number = Util::parsePhone($beneficiary->cell_phone_number);
        return $beneficiary;

    }
}
