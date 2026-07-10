<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;
use Muserpol\Models\Affiliate;
use Illuminate\Support\Collection;
use Muserpol\Models\ObservationType;
use Illuminate\Support\Str;

class AffiliateObservationSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    private $observation_type;
    private $affiliates;

    public function __construct(ObservationType $observation_type, Collection $affiliates)
    {
        $this->observation_type = $observation_type;
        $this->affiliates = $affiliates;
    }

    public function collection()
    {
        $data = collect([]);
        foreach ($this->affiliates as $a) {
            
            $observation = $a->observations->where('id',$this->observation_type->id)->last();
            // if ($a->observations->contains($this->observation_type->id)) {
            if ($observation) {
                
                $a->observation_name = $this->observation_type->name;
                $a->deleted_at = $observation->pivot->deleted_at;
                $data->push($a);
            }
        }
        return $data;
    }    
    /**
     * @return string
     */
    public function title(): string
    {
        return Str::limit(collect(explode('-', $this->observation_type->shortened))->last(), 25);
    }
    public function headings(): array
    {
        $new_columns = ['Nombre observacion','Fecha de eliminacion'];
        $default = [
            // 'NRO',
            'NUP',
            'CI',
            // 'CI COMPLETO ',
            "Primer Nombre ",
            "Segundo Nombre ",
            "Paterno ",
            "Materno ",
            "Apellido casada ",
            "Fecha Nacimiento ",
            "NUA",
        ];
        return array_merge($default, $new_columns);
    }
}
