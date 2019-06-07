<?php

namespace Muserpol\Observers;

use Log;
use Auth;
use Muserpol\Helpers\Util;
use Muserpol\Models\Workflow\WorkflowState;
use Carbon\Carbon;
use Muserpol\Models\EconomicComplement\EcoComBeneficiary;

class EcoComBeneficiaryObserver
{
    public function updating(EcoComBeneficiary $eco_com_beneficiary)
    {
        $old = EcoComBeneficiary::find($eco_com_beneficiary->id);
        $message = 'El usuario ' . Auth::user()->username . ' modifico ';
        $temp = $message;
     
    //  "date_death",
    //  "reason_death",
    //  "death_certificate_number",
     
        if ($eco_com_beneficiary->city_identity_card_id != $old->city_identity_card_id) {
            $message = $message . ' extension de la cedula de identidad de ' . $old->city_identity_card->name . ' a ' . $eco_com_beneficiary->city_identity_card->name . ', ';
        }
        if ($eco_com_beneficiary->identity_card != $old->identity_card) {
            $message = $message . 'la cedula de identidad de ' . $old->identity_card . ' a ' . $eco_com_beneficiary->identity_card . ', ';
        }
        if ($eco_com_beneficiary->last_name != $old->last_name) {
            $message = $message . 'el apellido paterno de ' . $old->last_name . ' a ' . $eco_com_beneficiary->last_name . ', ';
        }
        if ($eco_com_beneficiary->mothers_last_name != $old->mothers_last_name) {
            $message = $message . 'el apellido materno de ' . $old->mothers_last_name . ' a ' . $eco_com_beneficiary->mothers_last_name . ', ';
        }
        if ($eco_com_beneficiary->first_name != $old->first_name) {
            $message = $message . 'el primer nombre de ' . $old->first_name . ' a ' . $eco_com_beneficiary->first_name . ', ';
        }
        if ($eco_com_beneficiary->second_name != $old->second_name) {
            $message = $message . 'el segundo nombre de ' . $old->second_name . ' a ' . $eco_com_beneficiary->second_name . ', ';
        }
        if ($eco_com_beneficiary->surname_husband != $old->surname_husband) {
            $message = $message . 'el apellido de casada de ' . $old->surname_husband . ' a ' . $eco_com_beneficiary->surname_husband . ', ';
        }
        if ($eco_com_beneficiary->birth_date != $old->birth_date) {
            $message = $message . ' la fecha de nacimiento de ' . $old->birth_date . ' a ' . $eco_com_beneficiary->birth_date . ', ';
        }
        if ($eco_com_beneficiary->nua != $old->nua) {
            $message = $message . ' el nua de ' . $old->nua . ' a ' . $eco_com_beneficiary->nua . ', ';
        }
        if ($eco_com_beneficiary->gender != $old->gender) {
            $message = $message . ' el genero de ' . $old->gender . ' a ' . $eco_com_beneficiary->gender . ', ';
        }
        if ($eco_com_beneficiary->civil_status != $old->civil_status) {
            $message = $message . ' el estado civil de ' . $old->civil_status . ' a ' . $eco_com_beneficiary->civil_status . ', ';
        }
        if ($eco_com_beneficiary->phone_number != $old->phone_number) {
            $message = $message . ' el numero telefónico  de ' . $old->phone_number . ' a ' . $eco_com_beneficiary->phone_number . ', ';
        }
        if ($eco_com_beneficiary->cell_phone_number != $old->cell_phone_number) {
            $message = $message . ' el numero celular de ' . $old->cell_phone_number . ' a ' . $eco_com_beneficiary->cell_phone_number . ', ';
        }
        if ($eco_com_beneficiary->city_birth_id != $old->city_birth_id) {
            $message = $message . ' el lugar de nacimiento de ' . $old->city_birth->name . ' a ' . $eco_com_beneficiary->city_birth->name . ', ';
        }

        if ($eco_com_beneficiary->due_date != $old->due_date) {
            $message = $message . ' la fecha de vencimiento del ci de ' . $old->due_date . ' a ' . $eco_com_beneficiary->due_date . ', ';
        }
        if ($eco_com_beneficiary->is_duedate_undefined != $old->is_duedate_undefined) {
            $message = $message . ' el ... de ' . $old->is_duedate_undefined . ' a ' . $eco_com_beneficiary->is_duedate_undefined . ', ';
        }
        if($temp !=  $message){
            $message = $message . ' ';
            $eco_com_beneficiary->economic_complement->procedure_records()->create([
                'user_id' => Auth::user()->id,
                'record_type_id' => 11,
                'wf_state_id' => Util::getRol()->wf_states->first()->id,
                'date' => Carbon::now(),
                'message' => $message
            ]);
        }
    }
}
