<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Muserpol\Models\Affiliate;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class EcoComPromedioReport implements FromCollection, WithHeadings
{
    protected $report_type_id;
    protected $eco_com_procedure_id;
    protected $date;
    public function __construct(int $report_type_id, int $id ,string $fecha)
    {
        $this->report_type_id = $report_type_id;
        $this->eco_com_procedure_id = $id;
        $this->date= $fecha;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = null;
        switch ($this->report_type_id) {
            case 23:
                $data = DB::select("select ec.degree_id, de.name, count(ec.degree_id) as vejez, sum(total_rent) as totalrenta, sum(total_rent)/count(ec.degree_id) promedio from economic_complements ec inner join degrees de on ec.degree_id=de.id where ec.eco_com_procedure_id=".$this->eco_com_procedure_id." and eco_com_modality_id in (1,4,8,6) and deleted_at is null and date(ec.created_at)<='".$this->date."' group by ec.degree_id, de.name order by ec.degree_id");
                break;
            case 24:
                $data = DB::select("select ec.degree_id, de.name, count(ec.degree_id) as vejez, sum(total_rent) as totalrenta, sum(total_rent)/count(ec.degree_id) promedio from economic_complements ec inner join degrees de on ec.degree_id=de.id where ec.eco_com_procedure_id=".$this->eco_com_procedure_id." and eco_com_modality_id in (2,5,3,10,12,9,11,7) and deleted_at is null and date(ec.created_at)<='".$this->date."' group by ec.degree_id, de.name order by ec.degree_id");
                break;
        }
        return collect($data);
    }
    public function headings(): array
    {
        $new_columns = [];
        
        $default = [
            'Grado id ',
            'Nombre Grado ',
            'Numero ',
            'Total Renta ',
            'Promedio '
        ];
        return array_merge($default, $new_columns);
    }
}
