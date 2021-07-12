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
use Muserpol\User;
use Auth;
use Carbon\Carbon;

class EcoComImportPagoFuturo implements ToCollection
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
        //$found2 = 0;
        $found2 = collect([]);
        $not_found = collect([]);
        $user = User::first();

        //$current_procedures = Util::getEcoComCurrentProcedure()->first();
        $current_procedures = 18;

        
        $pago_futuro_id = 31;
        $observation = ObservationType::find($pago_futuro_id);
        foreach ($rows as $row) {
            $affiliate_id = strval($row[0]);
            $eco_com = EconomicComplement::select('economic_complements.*')
                ->where('economic_complements.eco_com_procedure_id', $current_procedures)
                 ->where('affiliate_id', $affiliate_id)->first();
            if ($eco_com) { 
                 if (!$eco_com->hasObservationType($pago_futuro_id)) {
                     $eco_com->observations()->save($observation, [
                         'user_id' => Auth::user()->id,
                         'date' => now(),
                         'message' => "Observación Importada II/2020",
                         'enabled' => true
                     ]);
                    }              
                    $eco_com->calculateTotalRentAps();
                    $total_rent = $eco_com->total_rent;
                    if ($total_rent > 0) {
                        $total = $total_rent * 2.03 / 100;
                        $aux = $total * 6;
                        $discount_type = DiscountType::findOrFail(7);
                        if ($eco_com->discount_types->contains($discount_type->id)) {
                            $eco_com->discount_types()->updateExistingPivot($discount_type->id, ['amount' => $aux, 'date' => now()]);
                        } else {
                            $eco_com->discount_types()->save($discount_type, ['amount' => $aux, 'date' => now()]);
                        }
                    
                        $found++;
                    }else{
                        $not_found->push($affiliate_id);
                    }
                
            }else {
                $not_found->push($affiliate_id);
            }
 
        }



       /* foreach ($rows as $row) {
            $ci = strval($row[0]);
            $affiliate = Affiliate::where('identity_card', $ci)->first();
            if (!$affiliate) {
                $spouse = Spouse::where('identity_card', $ci)->first();
                if ($spouse) {
                $affiliate = $spouse->affiliate;     
                }else{
                    $found2->push($ci);
                }
            }
            if($affiliate){
                $not_found->push($affiliate->id);
            }

        }*/

        /*
        foreach ($rows as $row) {
            
            $ci = strval($row[0]);
            $affiliate = Affiliate::where('identity_card', $ci)->first();
            
            if (!$affiliate) {
                $spouse = Spouse::where('identity_card', $ci)->first();
                if ($spouse) {
                $affiliate = $spouse->affiliate;     
                }else{
                    $not_found->push($ci);
                }    
            }
            else{
                
                $observation = ObservationType::find(2);
                $affiliate->observations()->save($observation, [
                    'user_id' => $user->id,
                    'date' => Carbon::now(),
                    'message' => 'PRIORITARIO - AFILIADOS EN MORA VILLA SALOME',
                    'enabled' => false
                ]);

                --$eco_coms = $affiliate->economic_complements()->whereIn('eco_com_procedure_id', Util::getEcoComCurrentProcedure())->get();
                foreach ($eco_coms as $eco) {
                    if (!$eco->hasObservationType(2) && $eco->eco_com_state_id == 16) {
                        $eco->observations()->save($observation, [
                            'user_id' => $user->id,
                            'date' => Carbon::now(),
                            'message' => 'PRIORITARIO - AFILIADOS EN MORA',
                            'enabled' => false
                        ]);
                        $found2++;
                
                     }

                }--

                $found++;
                
            }


        } 

        */

        $data = [
            'found' => $found,
            'found2' => $found2,
            'not_found' => $not_found,
        ];
        session()->put('pago_futuro_data', $data);
    }
}
