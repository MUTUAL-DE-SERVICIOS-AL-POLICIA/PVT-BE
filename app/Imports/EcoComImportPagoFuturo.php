<?php

namespace Muserpol\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Helpers\Util;
use Muserpol\Models\DiscountType;
use Muserpol\Models\EconomicComplement\EcoComProcedure;

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
        $not_found = collect([]);
        $eco_com_procedure = EcoComProcedure::find(14);
        //  EconomicComplementProcedure::whereYear('year', '=', $year)->where('semester', '=', $semester)->first();
        foreach ($rows as $row) {
            $ci = strval($row[1]); //ci
            $amount = Util::verifyAndParseNumber($row[11]); //semestral
            $eco_com = EconomicComplement::select('economic_complements.*')->leftJoin('eco_com_applicants', 'economic_complements.id', '=', 'eco_com_applicants.economic_complement_id')
                ->where('economic_complements.eco_com_procedure_id', $eco_com_procedure->id)
                ->whereRaw("ltrim(trim(eco_com_applicants.identity_card),'0') ='" . ltrim(trim($ci), '0') . "'")
                // ->where('eco_com_applicants.identity_card', $ci)
                ->first();
            if ($eco_com) {
                if (!Util::isDoblePerceptionEcoCom($ci)) {
                    $discount_type = DiscountType::findOrFail(7);
                    if ($eco_com->discount_types->contains($discount_type->id)) {
                        $eco_com->discount_types()->updateExistingPivot($discount_type->id, ['amount' => $amount, 'date' => now()]);
                    } else {
                        $eco_com->discount_types()->save($discount_type, ['amount' => $amount, 'date' => now()]);
                    }
                    $found++;
                }else{
                    logger("sii".$ci);
                }
            }else{
                $not_found->push($ci);
            }
        }
        $data = [
            'found' => $found,
            'not_found' => $not_found,
        ];
        logger($data);
        session()->put('pago_futuro_data', $data);
    }
}
