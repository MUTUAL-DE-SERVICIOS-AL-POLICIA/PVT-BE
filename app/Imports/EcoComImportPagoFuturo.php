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
        $found2 = 0;
        $not_found = collect([]);
        $user = User::first();

        $current_procedures = Util::getEcoComCurrentProcedure()->first();

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
                    'message' => 'PRIORITARIO - Préstamo con mora (generado automáticamente)',
                    'enabled' => false
                ]);

                $eco_coms = $affiliate->economic_complements()->whereIn('eco_com_procedure_id', Util::getEcoComCurrentProcedure())->get();
                foreach ($eco_coms as $eco) {
                    if (!$eco->hasObservationType(2) && $eco->eco_com_state_id == 16) {
                        $eco->observations()->save($observation, [
                            'user_id' => $user->id,
                            'date' => Carbon::now(),
                            'message' => 'PRIORITARIO - Préstamo con mora (generado automáticamente)',
                            'enabled' => false
                        ]);
                        $found++;
                
                     }

                }
                $found++;
                
            }


        }

        $data = [
            'found' => $found,
            'found2' => $found2,
            'not_found' => $not_found,
        ];
        logger($data);
        session()->put('pago_futuro_data', $data);
    }
}
