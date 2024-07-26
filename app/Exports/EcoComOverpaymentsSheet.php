<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Muserpol\Models\Affiliate;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class EcoComOverpaymentsSheet implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct()
    {

    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = null;
        $query = "SELECT a.id, ot.name, concat(a.first_name, ' ', a.second_name, ' ', a.last_name, ' ', a.mothers_last_name) as fullname, a.identity_card, d.total, d.balance, 
        case 
            when devs.totalamrt is null then 0
            else devs.totalamrt
        end as totalamrt
                    from devolutions d
                    join affiliates a on a.id = d.affiliate_id
                    join observation_types ot on d.observation_type_id = ot.id
                    left join (select ec.affiliate_id, sum(dtec2.amount) as totalamrt
                            from economic_complements ec
                            join eco_com_states ecs on ecs.id = ec.eco_com_state_id
                            join discount_type_economic_complement dtec2 on dtec2.economic_complement_id = ec.id
                            join discount_types dt on dt.id = dtec2.discount_type_id
                            join eco_com_state_types ecst on ecs.eco_com_state_type_id = ecst.id
                            where ecst.name = 'Pagado'
                            and dt.name = 'Amortización por Reposición de Fondos'
                            group by ec.affiliate_id ) as devs on a.id = devs.affiliate_id
                    where ot.shortened  = 'Cuentas por cobrar RF'
                    and d.deleted_at is null";
        $data = DB::select($query);
        return collect($data);
    }
    public function headings(): array
    {
        $new_columns = [];
        
        $default = [
            'NUP ',
            'Concepto',
            'Nombre Completo',
            'CI',
            'Total',
            'Amortizacion',
            'Deuda pendiente',
        ];
        return array_merge($default, $new_columns);
    }
}
