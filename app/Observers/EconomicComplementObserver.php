<?php

namespace Muserpol\Observers;

use Log;
use Auth;
use Muserpol\Helpers\Util;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\Workflow\WorkflowState;
use Carbon\Carbon;

class EconomicComplementObserver
{
    public function created(EconomicComplement $eco_com)
    {
        $eco_com->wf_records()->create([
            'user_id' => Auth::user()->id,
            'record_type_id' => 7,
            'wf_state_id' => $eco_com->wf_current_state_id,
            'date' => Carbon::now(),
            'message' => 'El usuario '.Auth::user()->username.' recepcionó el trámite.'
        ]);
        $eco_com->procedure_records()->create([
            'user_id' => Auth::user()->id,
            'record_type_id' => 7,
            'wf_state_id' => Util::getRol()->wf_states->first()->id,
            'date' => Carbon::now(),
            'message' => 'El usuario '.Auth::user()->username.' creó el trámite.'
        ]);
    }
    private function defaultValuesWfRecord($wf_current_state_id = null, $record_type_id = null, $message = null)
    {
        $default = [
            'user_id' => Auth::user()->id,
            'date' => Carbon::now(),
            'wf_state_id' => $wf_current_state_id,
            'record_type_id' => $record_type_id,
            'message' => $message,
        ];
        return $default;
    }
    public function updating(EconomicComplement $eco_com)
    {
        $old = EconomicComplement::find($eco_com->id);

        $message = 'El usuario ' . Auth::user()->username . ' modifico ';
        $temp = $message;
        if ($eco_com->city_id != $old->city_id) {
            $message = $message . ' regional de ' . optional($old->city)->name . ' a ' . optional($eco_com->city)->name . ', ';
        }
        if ($eco_com->reception_date != $old->reception_date) {
            $message = $message . ' fecha de recepción ' . $old->reception_date . ' a ' . $eco_com->reception_date . ', ';
        }
        if ($eco_com->sub_total_rent != $old->sub_total_rent) {
            $message = $message . ' Total Ganado Renta ó Pensión ' . $old->sub_total_rent . ' a ' . $eco_com->sub_total_rent . ', ';
        }
        if ($eco_com->reimbursement != $old->reimbursement) {
            $message = $message . ' Reintegro ' . $old->reimbursement . ' a ' . $eco_com->reimbursement . ', ';
        }
        if ($eco_com->dignity_pension != $old->dignity_pension) {
            $message = $message . ' Renta Dignidad ' . $old->dignity_pension . ' a ' . $eco_com->dignity_pension . ', ';
        }

        if ($eco_com->sub_total_rent != $old->sub_total_rent) {
            $message = $message . ' fecha de recepción ' . $old->sub_total_rent . ' a ' . $eco_com->sub_total_rent . ', ';
        }
        if ($eco_com->aps_total_fsa != $old->aps_total_fsa) {
            $message = $message . ' Fracción de Saldo Acumulada ' . $old->aps_total_fsa . ' a ' . $eco_com->aps_total_fsa . ', ';
        }
        if ($eco_com->aps_total_cc != $old->aps_total_cc) {
            $message = $message . ' Fracción de Pensión CCM ' . $old->aps_total_cc . ' a ' . $eco_com->aps_total_cc . ', ';
        }
        if ($eco_com->aps_total_fs != $old->aps_total_fs) {
            $message = $message . ' Fracción Solidaria de Vejéz ' . $old->aps_total_fs . ' a ' . $eco_com->aps_total_fs . ', ';
        }
        if ($eco_com->aps_disability != $old->aps_disability) {
            $message = $message . ' Prestación por Invalidéz ' . $old->aps_disability . ' a ' . $eco_com->aps_disability . ', ';
        }
        if ($eco_com->degree_id != $old->degree_id) {
            $message = $message . ' Grado ' . optional($old->degree)->name . ' a ' . optional($eco_com->degree)->name . ', ';
        }
        if ($eco_com->category_id != $old->category_id) {
            $message = $message . ' Categoría ' . optional($old->category)->name . ' a ' . optional($eco_com->category)->name . ', ';
        }
        if($temp !=  $message){
            $message = $message . ' ';
            $eco_com->procedure_records()->create([
                'user_id' => Auth::user()->id,
                'record_type_id' => 7,
                'wf_state_id' => Util::getRol()->wf_states->first()->id,
                'date' => Carbon::now(),
                'message' => $message
            ]);
        }

        $wf_state_sequence = WorkflowState::find($eco_com->wf_current_state_id)->sequence_number;
        $old_wf_state_sequence = WorkflowState::find($old->wf_current_state_id)->sequence_number;

        if ($eco_com->wf_current_state_id != $old->wf_current_state_id && $wf_state_sequence > $old_wf_state_sequence) {
            $eco_com->wf_records()->create($this->defaultValuesWfRecord($eco_com->wf_current_state_id, 3, "El usuario " . Auth::user()->username . " Derivó el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $eco_com->wf_state->name));
        }
        if ($eco_com->wf_current_state_id != $old->wf_current_state_id && $wf_state_sequence < $old_wf_state_sequence) {
            $eco_com->wf_records()->create($this->defaultValuesWfRecord($eco_com->wf_current_state_id, 4, "El usuario " . Auth::user()->username . " Devolvió el trámite " . $old->code . " de " . $old->wf_state->name . " a " . $eco_com->wf_state->name . " con nota: " . request()->message . "."));
        }
        if ($old->inbox_state == false && $eco_com->inbox_state == true && $eco_com->wf_current_state_id == $old->wf_current_state_id) {
            $eco_com->wf_records()->create($this->defaultValuesWfRecord($eco_com->wf_current_state_id, 1, 'El usuario ' . Auth::user()->username . ' Validó el trámite.'));
        }
        if ($old->inbox_state == true && $eco_com->inbox_state == false && $eco_com->wf_current_state_id == $old->wf_current_state_id) {
            $eco_com->wf_records()->create($this->defaultValuesWfRecord($eco_com->wf_current_state_id, 2, 'El usuario ' . Auth::user()->username . ' Canceló el trámite.'));
        }
    }
}
