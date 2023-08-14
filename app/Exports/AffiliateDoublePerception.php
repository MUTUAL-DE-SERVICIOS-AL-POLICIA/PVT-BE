<?php

namespace Muserpol\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Muserpol\Models\Affiliate;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;
class AffiliateDoublePerception  implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        $data = null;
        $query = "select a.id as nup, a.identity_card, CONCAT(a.first_name, ' ', a.second_name, ' ', a.last_name, ' ', a.mothers_last_name) as police_full_name
        from affiliates a 
        where a.identity_card in (
            select eca.identity_card
            from economic_complements ec 
            join eco_com_applicants eca on eca.economic_complement_id = ec.id
            and ec.eco_com_procedure_id = (select ecp.id
                                            from eco_com_procedures ecp 
                                            order by ecp.id desc
                                            limit 1)
            group by eca.identity_card
            having count(eca.identity_card) > 1
        )
        order by nup";
        $data = DB::select($query);
        $data = collect($data);
        return $data;
    }
    public function headings(): array
    {
        $new_columns = [];
        
        $default = [
            'NUP ',
            'CI',
            'Nombre Completo',
        ];
        return array_merge($default, $new_columns);
    }
}
