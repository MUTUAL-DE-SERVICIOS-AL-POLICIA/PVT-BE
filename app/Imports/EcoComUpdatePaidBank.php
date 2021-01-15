<?php

namespace Muserpol\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Helpers\Util;
use Muserpol\Models\DiscountType;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\ObservationType;
use Muserpol\Models\Affiliate;
use Muserpol\Models\Spouse;
use Muserpol\Models\FinancialEntity;
use Muserpol\User;
use Auth;
use Carbon\Carbon;

class EcoComUpdatePaidBank implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        ini_set('max_input_time', '-1');
        set_time_limit('-1');

        $found = 0;
        $not_found = collect([]);
        $not_found_t = collect([]);
        $user = User::first();
        //$status = array("ACTIVO", "ELABORADO", "SIN REGISTRO", "VALIDADO");
        $current_procedure = Util::getEcoComCurrentProcedure()->first();
        $current_procedure = 16;

        foreach ($rows as $row) {
            
            $nup = strval($row[0]); 
            $affiliate = Affiliate::where('id', $nup)->first();     
            if ($affiliate) {
                $eco_coms = $affiliate->economic_complements()->where('eco_com_procedure_id', $current_procedure)->get();            
                foreach ($eco_coms as $eco) {
                    //if ( $eco->eco_com_state_id == 24) {
                        $eco->wf_current_state_id = 8;
                        $eco->inbox_state = false;
                        $eco->save();
                        $found++;
                    //}else{
                    //    $not_found_t->push($nup);
                    //}
                }                  
            }else{
                $not_found->push($nup);
            }*/



            /*$ci = strval($row[0]); //ci
            $eco_com = EconomicComplement::select('economic_complements.*')->leftJoin('eco_com_applicants', 'economic_complements.id', '=', 'eco_com_applicants.economic_complement_id')
                ->where('economic_complements.eco_com_procedure_id', $current_procedure)
                ->where('economic_complements.eco_com_state_id', 25)
                ->whereRaw("ltrim(trim(eco_com_applicants.identity_card),'0') ='" . ltrim(trim($ci), '0') . "'")
                ->first();
            if ($eco_com) {
                if (!Util::isDoblePerceptionEcoCom($ci)) {
                        if ( $eco_com->eco_com_state_id == 25) {
                            $eco_com->eco_com_state_id = 16;
                            $eco_com->save();

                            $affiliate = Affiliate::where('id', $eco_com->affiliate_id)->first();
                            $affiliate->sigep_status = NULL;
                            $affiliate->save();

                            $found++;
                        logger($ci);
                }
                } else {
                    logger("------------------- si doble " . $ci);
                }
            } else {
                $not_found->push($ci);
            }*/




            /*
            $nup = strval($row[0]);
            $sigep_status = strval($row[14]);  
            $financial_entity_id = strval($row[15]) ? FinancialEntity::where('name', strval($row[15]))->first()->id : null;
            $account_number = strval($row[16]) ? strval($row[16]): null;          
            $affiliate = Affiliate::where('id', $nup)->first();
            if ($affiliate) {
                if (in_array($sigep_status, $status)) {
                    $affiliate->account_number = $account_number;
                    $affiliate->financial_entity_id = $financial_entity_id;
                    $affiliate->sigep_status = $sigep_status; 
                    $affiliate->save();
                    $found++;
                    logger($sigep_status. "/".$account_number. "/" . $financial_entity_id);
                }
                else {
                    $not_found_t->push($nup);
                }
            }else {
                $not_found_t->push($nup);
            }*/

            /*
            $nup = strval($row[0]); 
            $affiliate = Affiliate::where('id', $nup)->first();     
            if ($affiliate) {
                $eco_coms = $affiliate->economic_complements()->where('eco_com_procedure_id', $current_procedure)->get();            
                foreach ($eco_coms as $eco) {
                    if ( $eco->eco_com_state_id == 24) {
                        $eco->eco_com_state_id = 1;
                        $eco->save();
                        $found++;
                    }else{
                        $not_found_t->push($nup);
                    }
                }                  
            }else{
                $not_found->push($nup);
            }
            */





            /*
            $nup = strval($row[0]);   
            $affiliate = Affiliate::where('id', $nup)->first();
            if ($affiliate) {
                $eco_coms = $affiliate->economic_complements()->where('eco_com_procedure_id', $current_procedure)->get();            
                foreach ($eco_coms as $eco) {
                    if ( $eco->eco_com_state_id == 16) {
                        $eco->eco_com_state_id = 25;
                        $eco->save();
                        $found++;
                     }else{
                       $not_found_t->push($nup);
                    } 
                }                  
            }else{
                $not_found->push($nup);
            }*/
        }

        $data = [
            'found' => $found,
            'not_found' => $not_found,
            'not_found_t' => $not_found_t,
        ];
        /* logger($data); */
        session()->put('pago_banco_data', $data);
    }
}
