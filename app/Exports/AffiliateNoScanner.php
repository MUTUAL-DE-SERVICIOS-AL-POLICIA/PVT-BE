<?php

namespace Muserpol\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Muserpol\Models\Affiliate;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;
class AffiliateNoScanner  implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        $data = null;
        $query = "SELECT distinct a.id, a.identity_card, concat(a.first_name, ' ', a.second_name, ' ', a.last_name, ' ', a.mothers_last_name) as fullname, d.name as degree,c.name as category
        from affiliates a 
        inner join economic_complements ec on a.id = ec.affiliate_id--9.513
        left join degrees d on d.id = a.degree_id 
        left join categories c on c.id = a.category_id";
        $data = DB::select($query);
        $data = collect($data);

        $datas = collect([]);
        foreach($data as $d){
            $affiliate = Affiliate::find($d->id);
            //if(!$affiliate->hasDocumentScan()){
                $d->scan = $affiliate->hasDocumentScan()? 'SI':'NO';
                $datas->push($d);
            //}
        }
        return $datas;
    }
    public function headings(): array
    {
        $new_columns = [];
        
        $default = [
            'NUP ',
            'CI',
            'Nombre Completo afiliado',
            'Grado',
            'Categoría',
            '¿Documentos escaneados?'
        ];
        return array_merge($default, $new_columns);
    }
}
