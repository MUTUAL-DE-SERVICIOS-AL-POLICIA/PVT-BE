<?php 
namespace Muserpol\Observers;

use Log;
use Auth;
use Muserpol\Models\AffiliateRecord;
use Muserpol\Models\EconomicComplement\EcoComFixedPension;
use Carbon\Carbon;

class EcoComFixedPensionObserver
{
    public function updating(EcoComFixedPension $fixed_pension)
    {
        $old = $fixed_pension->getOriginal(); // Usamos getOriginal para obtener los valores anteriores
        $message = 'El usuario ' . (Auth::check() ? Auth::user()->username : 'desconocido') . ' modificó ';
        $changes = [];

        // Comparar cada campo y agregar al arreglo $changes si cambia
        if ($fixed_pension->sub_total_rent != $old['sub_total_rent']) {
            $changes[] = 'fecha de recepción de ' . $old['sub_total_rent'] . ' a ' . $fixed_pension->sub_total_rent;
        }
        if ($fixed_pension->rent_type != $old['rent_type']) {
            $changes[] = 'el registro de rentas de ' . $old['rent_type'] . ' a ' . $fixed_pension->rent_type;
        }
        if ($fixed_pension->reimbursement != $old['reimbursement']) {
            $changes[] = 'Reintegro de ' . $old['reimbursement'] . ' a ' . $fixed_pension->reimbursement;
        }
        if ($fixed_pension->dignity_pension != $old['dignity_pension']) {
            $changes[] = 'Renta Dignidad de ' . $old['dignity_pension'] . ' a ' . $fixed_pension->dignity_pension;
        }
        if ($fixed_pension->aps_total_fsa != $old['aps_total_fsa']) {
            $changes[] = 'Fracción de Saldo Acumulada de ' . $old['aps_total_fsa'] . ' a ' . $fixed_pension->aps_total_fsa;
        }
        if ($fixed_pension->aps_total_cc != $old['aps_total_cc']) {
            $changes[] = 'Fracción de Pensión CCM de ' . $old['aps_total_cc'] . ' a ' . $fixed_pension->aps_total_cc;
        }
        if ($fixed_pension->aps_total_fs != $old['aps_total_fs']) {
            $changes[] = 'Fracción Solidaria de Vejez de ' . $old['aps_total_fs'] . ' a ' . $fixed_pension->aps_total_fs;
        }
        if ($fixed_pension->aps_disability != $old['aps_disability']) {
            $changes[] = 'Prestación por Invalidez de ' . $old['aps_disability'] . ' a ' . $fixed_pension->aps_disability;
        }
        if ($fixed_pension->aps_total_death != $old['aps_total_death']) {
            $changes[] = 'Fracción por Muerte de ' . $old['aps_total_death'] . ' a ' . $fixed_pension->aps_total_death;
        }
        if ($fixed_pension->eco_com_procedure_id != $old['eco_com_procedure_id']) {
            $changes[] = 'el periódo de renta ' . $old['eco_com_procedure_id'] . ' a ' . $fixed_pension->eco_com_procedure_id;
        }

        // Si hay cambios, crear el mensaje y registrar el AffiliateRecord
        if (!empty($changes)) {
            $message .= implode(', ', $changes) . ' de la pensión Fija del afiliado.';
            $affiliate_record = new AffiliateRecord;
            $affiliate_record->user_id = Auth::user()->id;
            $affiliate_record->affiliate_id = $fixed_pension->affiliate_id;
            $affiliate_record->message = $message;
            $affiliate_record->save();
        }
    }
}
