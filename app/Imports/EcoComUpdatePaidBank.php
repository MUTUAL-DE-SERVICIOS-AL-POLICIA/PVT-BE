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

        $current_procedure = Util::getEcoComCurrentProcedure()->first();
        $current_procedure = 15;
         foreach ($rows as $row) {
            
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
