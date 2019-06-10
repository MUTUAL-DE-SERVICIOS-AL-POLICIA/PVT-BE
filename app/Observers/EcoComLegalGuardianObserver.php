<?php

namespace Muserpol\Observers;

use Log;
use Auth;
use Muserpol\Helpers\Util;
use Muserpol\Models\Workflow\WorkflowState;
use Carbon\Carbon;
use Muserpol\Models\EconomicComplement\EcoComLegalGuardian;

class EcoComLegalGuardianObserver
{
    public function created(EcoComLegalGuardian $eco_com_legal_guardian)
    {
        $message = 'El usuario ' . Auth::user()->username . ' creó Apoderado legal';
        $eco_com_legal_guardian->economic_complement->procedure_records()->create([
            'user_id' => Auth::user()->id,
            'record_type_id' => 12,
            'wf_state_id' => Util::getRol()->wf_states->first()->id,
            'date' => Carbon::now(),
            'message' => $message
        ]);
    }
    public function updating(EcoComLegalGuardian $eco_com_legal_guardian)
    {
        $old = EcoComLegalGuardian::find($eco_com_legal_guardian->id);
        $message = 'El usuario ' . Auth::user()->username . ' modifico los datos del Apoderado:  ';
        $temp = $message;
    //  "date_death",
    //  "reason_death",
    //  "death_certificate_number",
     
        if ($eco_com_legal_guardian->city_identity_card_id != $old->city_identity_card_id) {
            $message = $message . ' extension de la cedula de identidad de ' . optional($old->city_identity_card)->name . ' a ' . optional($eco_com_legal_guardian->city_identity_card)->name . ', ';
        }
        if ($eco_com_legal_guardian->identity_card != $old->identity_card) {
            $message = $message . 'la cedula de identidad de ' . $old->identity_card . ' a ' . $eco_com_legal_guardian->identity_card . ', ';
        }
        if ($eco_com_legal_guardian->last_name != $old->last_name) {
            $message = $message . 'el apellido paterno de ' . $old->last_name . ' a ' . $eco_com_legal_guardian->last_name . ', ';
        }
        if ($eco_com_legal_guardian->mothers_last_name != $old->mothers_last_name) {
            $message = $message . 'el apellido materno de ' . $old->mothers_last_name . ' a ' . $eco_com_legal_guardian->mothers_last_name . ', ';
        }
        if ($eco_com_legal_guardian->first_name != $old->first_name) {
            $message = $message . 'el primer nombre de ' . $old->first_name . ' a ' . $eco_com_legal_guardian->first_name . ', ';
        }
        if ($eco_com_legal_guardian->second_name != $old->second_name) {
            $message = $message . 'el segundo nombre de ' . $old->second_name . ' a ' . $eco_com_legal_guardian->second_name . ', ';
        }
        if ($eco_com_legal_guardian->surname_husband != $old->surname_husband) {
            $message = $message . 'el apellido de casada de ' . $old->surname_husband . ' a ' . $eco_com_legal_guardian->surname_husband . ', ';
        }
        if ($eco_com_legal_guardian->birth_date != $old->birth_date) {
            $message = $message . ' la fecha de nacimiento de ' . $old->birth_date . ' a ' . $eco_com_legal_guardian->birth_date . ', ';
        }
        if ($eco_com_legal_guardian->gender != $old->gender) {
            $message = $message . ' el genero de ' . $old->gender . ' a ' . $eco_com_legal_guardian->gender . ', ';
        }
        if ($eco_com_legal_guardian->phone_number != $old->phone_number) {
            $message = $message . ' el numero telefónico  de ' . $old->phone_number . ' a ' . $eco_com_legal_guardian->phone_number . ', ';
        }
        if ($eco_com_legal_guardian->cell_phone_number != $old->cell_phone_number) {
            $message = $message . ' el numero celular de ' . $old->cell_phone_number . ' a ' . $eco_com_legal_guardian->cell_phone_number . ', ';
        }

        if ($eco_com_legal_guardian->due_date != $old->due_date) {
            $message = $message . ' la fecha de vencimiento del ci de ' . $old->due_date . ' a ' . $eco_com_legal_guardian->due_date . ', ';
        }
        if ($eco_com_legal_guardian->is_duedate_undefined != $old->is_duedate_undefined) {
            $message = $message . ' el ... de ' . $old->is_duedate_undefined . ' a ' . $eco_com_legal_guardian->is_duedate_undefined . ', ';
        }
        if ($eco_com_legal_guardian->eco_com_legal_guardian_type_id != $old->eco_com_legal_guardian_type_id) {
            $message = $message . ' el tipo de apoderado de ' . optional($old->eco_com_legal_guardian_type)->name . ' a ' . optional($eco_com_legal_guardian->eco_com_legal_guardian_type)->name . ', ';
        }
        if ($eco_com_legal_guardian->number_authority != $old->number_authority) {
            $message = $message . ' el Nro de Poder de ' . $old->number_authority . ' a ' . $eco_com_legal_guardian->number_authority . ', ';
        }
        if ($eco_com_legal_guardian->notary_of_public_faith != $old->notary_of_public_faith) {
            $message = $message . ' Notaria de Fe Publica Nro de' . $old->notary_of_public_faith . ' a ' . $eco_com_legal_guardian->notary_of_public_faith . ', ';
        }
        if ($eco_com_legal_guardian->notary != $old->notary) {
            $message = $message . ' el Notario de' . $old->notary . ' a ' . $eco_com_legal_guardian->notary . ', ';
        }
        if ($eco_com_legal_guardian->date_authority != $old->date_authority) {
            $message = $message . ' la Fecha de Poder de' . $old->date_authority . ' a ' . $eco_com_legal_guardian->date_authority . ', ';
        }

        if($temp !=  $message){
            $message = $message . ' ';
            $eco_com_legal_guardian->economic_complement->procedure_records()->create([
                'user_id' => Auth::user()->id,
                'record_type_id' => 12,
                'wf_state_id' => Util::getRol()->wf_states->first()->id,
                'date' => Carbon::now(),
                'message' => $message
            ]);
        }
    }
    public function deleted(EcoComLegalGuardian $eco_com_legal_guardian)
    {
        $message = 'El usuario ' . Auth::user()->username . ' eliminó Apoderado legal';
        $eco_com_legal_guardian->economic_complement->procedure_records()->create([
            'user_id' => Auth::user()->id,
            'record_type_id' => 12,
            'wf_state_id' => Util::getRol()->wf_states->first()->id,
            'date' => Carbon::now(),
            'message' => $message
        ]);
    }
}
