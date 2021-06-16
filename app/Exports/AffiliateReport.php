<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Muserpol\Models\Affiliate;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AffiliateReport implements FromCollection, WithHeadings, ShouldAutoSize, WithMultipleSheets
{
    protected $report_type_id;
    public function __construct($report_type_id)
    {
        $this->report_type_id = $report_type_id;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = null;
        $columns = "";
        switch ($this->report_type_id) {
            case 9:
                // $columns = ', observation_types.name as observaciones';
                $columns = '';
                $data = Affiliate::select(DB::raw(Affiliate::basic_info_colums() . $columns))
                    ->affiliateinfo()
                    ->observationType()
                    ->whereHas('observations', function ($query) {
                        $query->whereIn('observation_type_id', [13]);
                    })
                    ->toSql();
                break;
                break;

            default:
                # code...
                break;
        }
        return $data;
    }
    public function headings(): array
    {
        $new_columns = [];
        switch ($this->report_type_id) {
            case 9:
                $new_columns = [
                    'observaciones'
                ];
                break;
        }
        $default = [
            'NRO',
            'NUP',
            'CI',
            'CI Exp ',
            'CI COMPLETO ',
            "Primer Nombre ",
            "Segundo Nombre ",
            "Paterno ",
            "Materno ",
            "Apellido casda ",
            "Fecha Nacimiento ",
            "NUA",
        ];
        return array_merge($default, $new_columns);
    }
}
