<?php

namespace Muserpol\Imports;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Helpers\Util;
use Muserpol\Models\EconomicComplement\EconomicComplement;

class EcoComImportSenasir implements ToCollection
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function collection(Collection $rows)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        ini_set('max_input_time', '-1');
        set_time_limit('-1');
        $found = 0;
        $not_found = collect([]);
        $eco_com_procedure = EcoComProcedure::find(14);
        //  EconomicComplementProcedure::whereYear('year', '=', $year)->where('semester', '=', $semester)->first();
        foreach ($rows as $row) {
            $ext = ($row[9] ? "-" . $row[9] : '');
            $ext = str_replace(' ', '', $ext);
            $ci = trim(Util::removeSpaces(trim($row[8])) . ((trim(Util::removeSpaces($row[9])) != '') ? '-' . $row[9] : ''));
            if ($row[6] == "DERECHOHABIENTE") { // viudedad
                $eco_com = EconomicComplement::select('economic_complements.*')
                    ->NotHasEcoComState(1, 6)
                    ->leftJoin('eco_com_applicants', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')
                    ->leftJoin('affiliates', 'economic_complements.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('eco_com_modalities', 'economic_complements.eco_com_modality_id', '=', 'eco_com_modalities.id')
                    ->leftJoin('procedure_modalities', 'procedure_modalities.id', '=', 'eco_com_modalities.procedure_modality_id')
                    ->where('eco_com_procedure_id', $eco_com_procedure->id)
                    ->where('affiliates.pension_entity_id', '=', 5)
                    ->where('procedure_modalities.id', '=', 30)
                    // realiza la comparacion: eliminando 0 de la izq (se mantiene la extencion e.g. '-1K')
                    ->whereRaw("ltrim(trim(eco_com_applicants.identity_card),'0') ='" . ltrim(trim($ci), '0') . "'")
                    ->first();
            } elseif ($row[6] == "TITULAR") { //vejez
                $eco_com = EconomicComplement::select('economic_complements.*')
                    ->NotHasEcoComState(1, 6)
                    ->leftJoin('eco_com_applicants', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')
                    ->leftJoin('affiliates', 'economic_complements.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('eco_com_modalities', 'economic_complements.eco_com_modality_id', '=', 'eco_com_modalities.id')
                    ->leftJoin('procedure_modalities', 'procedure_modalities.id', '=', 'eco_com_modalities.procedure_modality_id')
                    ->where('eco_com_procedure_id', $eco_com_procedure->id)
                    ->where('affiliates.pension_entity_id', '=', 5)
                    ->where('procedure_modalities.id', '=', 29)
                    // realiza la comparacion: eliminando 0 de la izq (se mantiene la extencion e.g. '-1K')
                    ->whereRaw("ltrim(trim(eco_com_applicants.identity_card),'0') ='" . ltrim(trim($ci), '0') . "'")
                    ->first();
            } else {
                $eco_com = EconomicComplement::select('economic_complements.*')
                    ->NotHasEcoComState(1, 6)
                    ->leftJoin('eco_com_applicants', 'eco_com_applicants.economic_complement_id', '=', 'economic_complements.id')
                    ->leftJoin('affiliates', 'economic_complements.affiliate_id', '=', 'affiliates.id')
                    ->leftJoin('eco_com_modalities', 'economic_complements.eco_com_modality_id', '=', 'eco_com_modalities.id')
                    ->leftJoin('procedure_modalities', 'procedure_modalities.id', '=', 'eco_com_modalities.procedure_modality_id')
                    ->where('eco_com_procedure_id', $eco_com_procedure->id)
                    ->where('affiliates.pension_entity_id', '=', 5)
                    ->where('procedure_modalities.id', '=', 31)
                    // realiza la comparacion: eliminando 0 de la izq (se mantiene la extencion e.g. '-1K')
                    ->whereRaw("ltrim(trim(eco_com_applicants.identity_card),'0') ='" . ltrim(trim($ci), '0') . "'")
                    ->first();
            }
            if ($eco_com && $eco_com_procedure->indicator > 0) {
                // if ((is_null($eco_com->total_rent) || $eco_com->total_rent == 0) && $eco_com_procedure->indicator > 0) {
                if ($eco_com_procedure->indicator > 0) {
                    // $reimbursements = $row->reintegro_importe_adicional + $row->reintegro_inc_gestion;
                    $reimbursements = $row[35] + $row[39];
                    // $discount = $row->renta_dignidad + $row->reintegro_renta_dignidad + $row->reintegro_importe_adicional + $row->reintegro_inc_gestion;
                    $discount = $row[25] + $row[26] + $reimbursements;
                    // $total_rent = $datos->total_ganado - $discount;
                    $total_rent = $row[16] - $discount - $row[20];
                    if ($eco_com->isOldAge() && $total_rent < $eco_com_procedure->indicator) { //Vejez Senasir
                        $eco_com->eco_com_modality_id = 8;
                    } elseif ($eco_com->isWidowhood() && $total_rent < $eco_com_procedure->indicator) { //Viudedad
                        $eco_com->eco_com_modality_id = 9;
                    } elseif ($eco_com->isOrphanhood() && $total_rent < $eco_com_procedure->indicator) { //Orfandad
                        $eco_com->eco_com_modality_id = 12;
                    }
                    // $eco_com->sub_total_rent = $row->total_ganado;
                    $eco_com->sub_total_rent = $row[16];
                    $eco_com->total_rent = $total_rent;
                    // $eco_com->dignity_pension = $row->renta_dignidad;
                    $eco_com->dignity_pension = $row[25];
                    $eco_com->reimbursement = $reimbursements;
                    $eco_com->rent_type = 'Automatico';
                    $eco_com->save();
                    $found++;
                }
            } else {
                $not_found->push([
                    'ci' => $ci,
                    'paterno' => $row[10],
                    'materno' => $row[11],
                    'p_nombre' => $row[12],
                    's_nombre' => $row[13],
                ]);
            }
        }
        $data = [
            'found' => $found,
            // 'not_found' => $not_found,
        ];
        logger($data);
        session()->put('senasir_data', $data);
        return $data;
    }
}
