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
            case 13:
                $ecos_one = $eco_com_procedure->economic_complements;
                $ecos_second = $second_eco_com_procedure->economic_complements;
                $rows = collect([]);
                foreach ($ecos_one as $one) {
                    $afi_id = $one->affiliate_id;
                    $old = EconomicComplement::where('affiliate_id', '=', $afi_id)
                        ->where('eco_com_procedure_id', '=', $second_eco_com_procedure->id)
                        ->first();
                    if ($old) {
                        if (
                            ($one->aps_total_fsa > 0 && (is_null($old->aps_total_fsa) || $old->aps_total_fsa == 0) || (is_null($one->aps_total_fsa) || $one->aps_total_fsa == 0) && $old->aps_total_fsa > 0)
                            || ($one->aps_total_fs > 0 && (is_null($old->aps_total_fs) || $old->aps_total_fs == 0) || (is_null($one->aps_total_fs) || $one->aps_total_fs == 0) && $old->aps_total_fs > 0)
                            || ($one->aps_total_cc > 0 && (is_null($old->aps_total_cc) || $old->aps_total_cc == 0) || (is_null($one->aps_total_cc) || $one->aps_total_cc == 0) && $old->aps_total_cc > 0)
                            //|| ($one->aps_disability > 0 && is_null($old->aps_disability) || is_null($one->aps_disability) && $old->aps_disability > 0)
                        ) {
                            $rows->push(array(
                                'afiliado_nup' => $one->affiliate->id,
                                'afiliado_ci' => $one->affiliate->identity_card,
                                'tramite_anterior' => $old->code,
                                'tramite_actual' => $one->code,
                                "aps_total_FSA Anterior" => $one->aps_total_fsa,
                                "aps_total_FSA Actual" => $old->aps_total_fsa,
                                "aps_total_FS Anterior" => $one->aps_total_fs,
                                "aps_total_FS Actual" => $old->aps_total_fs,
                                "aps_total_CC Anterior" => $one->aps_total_cc,
                                "aps_total_CC Actual" => $old->aps_total_cc,
                                //"aps_total_invalidez Anterior" => $one->aps_disability,
                                //"aps_total_invalidez Actual" => $old->aps_disability,
                            ));
                        }
                    }
                }
                return $rows;
                break;
            case 19:
                $ecos_one = $eco_com_procedure->economic_complements()->with('eco_com_beneficiary')->get();
                // $ecos_second = $second_eco_com_procedure->economic_complements()->with('eco_com_beneficiary')->get();
                $rows = collect([]);
                foreach ($ecos_one as $one) {
                    $afi_id = $one->affiliate_id;
                    $old = EconomicComplement::with('eco_com_beneficiary')->where('affiliate_id', '=', $afi_id)
                        ->where('eco_com_procedure_id', '=', $second_eco_com_procedure->id)
                        ->first();
                    if ($old) {
                        if (
                            $one->eco_com_beneficiary->identity_card  != $old->eco_com_beneficiary->identity_card
                            || $one->eco_com_beneficiary->first_name  != $old->eco_com_beneficiary->first_name
                            || $one->eco_com_beneficiary->second_name  != $old->eco_com_beneficiary->second_name
                            || $one->eco_com_beneficiary->last_name  != $old->eco_com_beneficiary->last_name
                            || $one->eco_com_beneficiary->mothers_last_name  != $old->eco_com_beneficiary->mothers_last_name
                            || $one->eco_com_beneficiary->surname_husband  != $old->eco_com_beneficiary->surname_husband
                        ) {
                            $rows->push(array(
                                'afiliado_nup' => $one->affiliate->id,
                                'afiliado_ci' => $one->affiliate->identity_card,
                                'tramite_anterior' => $old->code,
                                'tramite_actual' => $one->code,
                                'ci_ben_tramite_anterior' => $old->eco_com_beneficiary->identity_card,
                                'ci_ben_tramite_actual' => $one->eco_com_beneficiary->identity_card,
                                'primer_nombre_ben_tramite_anterior' => $old->eco_com_beneficiary->first_name,
                                'primer_nombre_ben_tramite_actual' => $one->eco_com_beneficiary->first_name,
                                'segundo_nombre_ben_tramite_anterior' => $old->eco_com_beneficiary->second_name,
                                'segundo_nombre_ben_tramite_actual' => $one->eco_com_beneficiary->second_name,
                                'paterno_ben_tramite_anterior' => $old->eco_com_beneficiary->last_name,
                                'paterno_ben_tramite_actual' => $one->eco_com_beneficiary->last_name,
                                'materno_ben_tramite_anterior' => $old->eco_com_beneficiary->mothers_last_name,
                                'materno_ben_tramite_actual' => $one->eco_com_beneficiary->mothers_last_name,
                                'ap_casada_ben_tramite_anterior' => $old->eco_com_beneficiary->surname_husband,
                                'ap_casada_ben_tramite_actual' => $one->eco_com_beneficiary->surname_husband,
                            ));
                        }
                    }
                }
                return $rows;
                break;
            
                case 20:
            
                $ecos_one = $eco_com_procedure->economic_complements;
                $ecos_second = $second_eco_com_procedure->economic_complements;
                $rows = collect([]);
                foreach ($ecos_one as $current) {
                    $afi_id = $current->affiliate_id;
                    $old = EconomicComplement::where('affiliate_id', '=', $afi_id)
                        ->where('eco_com_procedure_id', '=', $second_eco_com_procedure->id)
                        ->first();
                    if ($old) {
                        if (
                            ((($old->aps_disability == 0 || is_null($old->aps_disability)) && $current->aps_disability>0)
                            ||(($current->aps_disability == 0 || is_null($current->aps_disability)) && $old->aps_disability>0)
                            ||$old->aps_disability > $current->aps_disability && $current->aps_disability !=0 ||$current->aps_disability > $old->aps_disability && $old->aps_disability >0 )
                            
                            ||((($old->aps_total_death == 0 || is_null($old->aps_total_death)) && $current->aps_total_death>0)
                            ||(($current->aps_total_death == 0 || is_null($current->aps_total_death)) && $old->aps_total_death>0)
                            ||$old->aps_total_death > $current->aps_total_death && $current->aps_total_death !=0 ||$current->aps_total_death > $old->aps_total_death && $old->aps_total_death >0 )
                            )
                            {
                            $rows->push(array(
                                'afiliado_nup' => $current->affiliate->id,
                                'afiliado_ci' => $current->affiliate->identity_card,
                                
                                'tramite_anterior' => $old->code,
                                'tramite_actual' => $current->code,
                                'Primer_nombre_beneficiario' => $current->affiliate->first_name,
                                'Segundo _nombre_beneficiario' => $current->affiliate->second_name,
                                'Paterno_beneficiario' => $current->affiliate->last_name,
                                'Materno _beneficiario' => $current->affiliate->mothers_last_name,
                                'Invalidez_Anterior' => $old->aps_disability,
                                'Invalidez_Actual' => $current->aps_disability,
                                'Muerte_Anterior' => $old->aps_total_death,
                                'Muerte_Actual' => $current->aps_total_death,
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
            case 13:
                $new_columns = [
                    "aps_total_FSA Anterior",
                    "aps_total_FSA Actual",
                    "aps_total_FS Anterior",
                    "aps_total_FS Actual",
                    "aps_total_CC Anterior",
                    "aps_total_CC Actual",
                    "aps_total_invalidez Anterior",
                    "aps_total_invalidez Actual",
                ];
                break;
            case 19:
                $new_columns = [
                    'ci_ben_tramite_anterior',
                    'ci_ben_tramite_actual',
                    'primer_nombre_ben_tramite_anterior',
                    'primer_nombre_ben_tramite_actual',
                    'segundo_nombre_ben_tramite_anterior',
                    'segundo_nombre_ben_tramite_actual',
                    'paterno_ben_tramite_anterior',
                    'paterno_ben_tramite_actual',
                    'materno_ben_tramite_anterior',
                    'materno_ben_tramite_actual',
                    'ap_casada_ben_tramite_anterior',
                    'ap_casada_ben_tramite_actual',
                ];
                break;

            case 20:
                $new_columns = [
                    "Primer_nombre_beneficiario",
                    "Segundo _nombre_beneficiario",
                    "Paterno_beneficiario",
                    "Materno_beneficiario",
                    "Invalidez_Anterior",
                    "Invalidez_Actual",
                    "Muerte_Anterior",
                    "Muerte_Actual",
                ];
                break;

            default:
                # code...
                break;
        }

        return array_merge($default, $new_columns);
    }
}
