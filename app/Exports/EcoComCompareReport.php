<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use DB;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\Degree;
use Muserpol\Models\Category;
use Muserpol\Models\EconomicComplement\EcoComRent;
use Carbon\Carbon;
use Muserpol\Models\ProcedureModality;

class EcoComCompareReport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $report_type_id;
    protected $eco_com_procedure_id;
    protected $second_eco_com_procedure_id;

    public function __construct($report_type_id, $eco_com_procedure_id, $second_eco_com_procedure_id)
    {
        $this->report_type_id = $report_type_id;
        $this->eco_com_procedure_id = $eco_com_procedure_id;
        $this->second_eco_com_procedure_id = $second_eco_com_procedure_id;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $eco_com_procedure = EcoComProcedure::find($this->eco_com_procedure_id);
        $second_eco_com_procedure = EcoComProcedure::find($this->second_eco_com_procedure_id);
        switch ($this->report_type_id) {
            case 10:
                $ecos_one = $eco_com_procedure->economic_complements;
                $ecos_second = $second_eco_com_procedure->economic_complements;
                $rows = collect([]);
                foreach ($ecos_one as $one) {
                    $afi_id = $one->affiliate_id;
                    $old = EconomicComplement::where('affiliate_id', '=', $afi_id)
                        ->where('eco_com_procedure_id', '=', $second_eco_com_procedure->id)
                        ->first();
                    if ($old) {
                        if ($one->degree_id != $old->degree_id) {
                            $rows->push(array(
                                'afiliado_nup' => $one->affiliate->id,
                                'afiliado_ci' => $one->affiliate->identity_card,
                                'tramite_anterior' => $old->code,
                                'tramite_actual' => $one->code,
                                'grado_anterior' => Degree::find($old->degree_id)->shortened,
                                'grado_actual' => Degree::find($one->degree_id)->shortened,
                            ));
                        }
                    }
                }
                return $rows;
                break;
            case 11:
                $ecos_one = $eco_com_procedure->economic_complements;
                $ecos_second = $second_eco_com_procedure->economic_complements;
                $rows = collect([]);
                foreach ($ecos_one as $one) {
                    $afi_id = $one->affiliate_id;
                    $old = EconomicComplement::where('affiliate_id', '=', $afi_id)
                        ->where('eco_com_procedure_id', '=', $second_eco_com_procedure->id)
                        ->first();
                    if ($old) {
                        if ($one->category_id != $old->category_id) {
                            $rows->push(array(
                                'afiliado_nup' => $one->affiliate->id,
                                'afiliado_ci' => $one->affiliate->identity_card,
                                'tramite_anterior' => $old->code,
                                'tramite_actual' => $one->code,
                                'categoria_anterior' => Category::find($old->category_id)->name,
                                'categoria_actual' => Category::find($one->category_id)->name,
                            ));
                        }
                    }
                }
                return $rows;
                break;
            case 12:
                $ren_old = EcoComRent::whereYear('year', '=', Carbon::parse($second_eco_com_procedure->year)->year)
                    ->where('semester', '=', $second_eco_com_procedure->semester)
                    ->get();
                $ren_current = EcoComRent::whereYear('year', '=', Carbon::parse($eco_com_procedure->year)->year)
                    ->where('semester', '=', $eco_com_procedure->semester)
                    ->get();
                $rows = collect([]);
                foreach ($ren_current as $current_rent) {
                    foreach ($ren_old as $old_rent) {
                        if ($current_rent->degree_id == $old_rent->degree_id && $current_rent->eco_com_type_id == $old_rent->eco_com_type_id) {
                            $rows->push(array(
                                'degree' => Degree::find($current_rent->degree_id)->shortened,
                                'year_old' => Carbon::parse($old_rent->year)->year,
                                'semester_old' => $old_rent->semester,
                                'average_old' => $old_rent->average,
                                'year_current' => Carbon::parse($current_rent->year)->year,
                                'semester_current' => $current_rent->semester,
                                'average_current' => $current_rent->average,
                                'difference' =>  $current_rent->average - $old_rent->average,
                                'modality' => ProcedureModality::find($current_rent->procedure_modality_id)->name ?? '',
                            ));
                        }
                    }
                }
                return $rows;
                break;

            default:
                # code...
                break;
        }
    }
    public function headings(): array
    {
        $default = [
            'afiliado_nup',
            'afiliado_ci',
            'tramite_anterior',
            'tramite_actual',
        ];
        $new_columns = [];
        switch ($this->report_type_id) {
            case 10:
                $new_columns = [
                    'grado_anterior',
                    'grado_actual'
                ];
                break;
            case 11:
                $new_columns = [
                    'categoria_anterior',
                    'categoria_actual'
                ];
                break;
            case 12:
                $default = [];
                $new_columns = [
                    'degree',
                    'year_old',
                    'semester_old',
                    'average_old',
                    'year_current',
                    'semester_current',
                    'average_current',
                    'difference',
                    'modality'
                ];
                break;
            default:
                # code...
                break;
        }

        return array_merge($default, $new_columns);
    }
}
