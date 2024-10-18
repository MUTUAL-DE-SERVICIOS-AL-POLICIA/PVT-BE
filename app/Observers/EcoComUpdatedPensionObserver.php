<?php

namespace Muserpol\Observers;

use Log;
use Auth;
use Muserpol\Helpers\Util;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\EconomicComplement\EcoComUpdatedPension;
use Carbon\Carbon;

class EcoComUpdatedPensionObserver
{
    public function updating(EcoComUpdatedPension $updated_pension)
    {
        $old = EcoComUpdatedPension::find($updated_pension->id);
        $message = 'El usuario ' . Auth::user()->username . ' modifico ';
        $temp = $message;
        if ($updated_pension->sub_total_rent != $old->sub_total_rent) {
            $message = $message . ' fecha de recepción ' . $old->sub_total_rent . ' a ' . $updated_pension->sub_total_rent . ' de la pensión para descuento de auxilio mortuorio, ';
        }
        if ($updated_pension->rent_type != $old->rent_type) {
            $message = $message . ' el registro de rentas de ' . $old->rent_type . ' a ' .$updated_pension->rent_type . ' de la pensión para descuento de auxilio mortuorio, ';
        }else {

            if ($updated_pension->reimbursement != $old->reimbursement) {
                $message = $message . ' Reintegro ' . $old->reimbursement . ' a ' . $updated_pension->reimbursement . ' de la pensión para descuento de auxilio mortuorio, ';
            }
            if ($updated_pension->dignity_pension != $old->dignity_pension) {
                $message = $message . ' Renta Dignidad ' . $old->dignity_pension . ' a ' . $updated_pension->dignity_pension . ' de la pensión para descuento de auxilio mortuorio, ';
            }

            if ($updated_pension->aps_total_fsa != $old->aps_total_fsa) {
                $message = $message . ' Fracción de Saldo Acumulada ' . $old->aps_total_fsa . ' a ' . $updated_pension->aps_total_fsa . ' de la pensión para descuento de auxilio mortuorio, ';
            }
            if ($updated_pension->aps_total_cc != $old->aps_total_cc) {
                $message = $message . ' Fracción de Pensión CCM ' . $old->aps_total_cc . ' a ' . $updated_pension->aps_total_cc . ' de la pensión para descuento de auxilio mortuorio, ';
            }
            if ($updated_pension->aps_total_fs != $old->aps_total_fs) {
                $message = $message . ' Fracción Solidaria de Vejéz ' . $old->aps_total_fs . ' a ' . $updated_pension->aps_total_fs . ' de la pensión para descuento de auxilio mortuorio, ';
            }
            if ($updated_pension->aps_disability != $old->aps_disability) {
                $message = $message . ' Prestación por Invalidéz ' . $old->aps_disability . ' a ' . $updated_pension->aps_disability . ' de la pensión para descuento de auxilio mortuorio, ';
            }
            if ($updated_pension->aps_total_death != $old->aps_total_death) {
                $message = $message . ' Fracción por Muerte ' . $old->aps_total_death . ' a ' . $updated_pension->aps_total_death . ' de la pensión para descuento de auxilio mortuorio, ';
            }
        }    
        if($temp !=  $message){
            $message = $message . ' ';
            $eco_com = EconomicComplement::find($updated_pension->economic_complement_id);
            $eco_com->procedure_records()->create([
                'user_id' => Auth::user()->id,
                'record_type_id' => 7,
                'wf_state_id' => Util::getRol()->wf_states->first()->id,
                'date' => Carbon::now(),
                'message' => $message
            ]);
        }
    }
}
