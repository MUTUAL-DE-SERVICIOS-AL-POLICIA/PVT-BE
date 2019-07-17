<?php

namespace Muserpol\Exports\PlanillaGeneral;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use DB;
use Muserpol\Models\ObservationType;
use Muserpol\Helpers\Util;
use Illuminate\Support\Str;

// class EcoComMoreObservationSheet implements FromView, WithTitle, WithHeadings, ShouldAutoSize
class EcoComMoreObservationSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    protected $eco_com_procedure_id;
    public function __construct($eco_com_procedure_id)
    {
        $this->eco_com_procedure_id = $eco_com_procedure_id;
    }
    // public function view(): View
    public function collection()
    {
        $columns = '';
        $eco_coms =  EconomicComplement::with('observations')->ecoComProcedure($this->eco_com_procedure_id)
            ->info()
            ->beneficiary()
            ->affiliateInfo()
            ->wfstates()
            ->where('economic_complements.wf_current_state_id', 3)
            ->whereIn('economic_complements.eco_com_state_id', [16,18])
            ->where('economic_complements.total', '>=', 0)
            ->has('observations')
            ->select(
                'economic_complements.id as id',
                'economic_complements.affiliate_id as NUP',
                'economic_complements.code as eco_com_code',
                'economic_complements.reception_date as fecha_recepcion',
                'beneficiary.identity_card as ci_ben',
                'beneficiary_city.first_shortened as ext_ben',
                // 'concat_ws(' ', beneficiary.identity_card,beneficiary_city.first_shortened) as ci_completo_ben,
                'beneficiary.first_name as primer_nombre_ben',
                'beneficiary.second_name as segundo_nombre_ben',
                'beneficiary.last_name as apellido_paterno_ben',
                'beneficiary.mothers_last_name as apellido_materno_ben',
                'beneficiary.surname_husband as apellido_de_casado_ben',
                'beneficiary.birth_date as fecha_nac_ben',
                'beneficiary.phone_number as telefonos_ben',
                'beneficiary.cell_phone_number as celulares_ben',
                'beneficiary.official as oficialia_ben',
                'beneficiary.book as libro_ben',
                'beneficiary.departure as partida_ben',
                'beneficiary.marriage_date as fecha_matrimonio_ben',
                'affiliates.identity_card as ci_causa',
                'affiliate_city.first_shortened as exp_causa',
                // concat_ws(' ', affiliates.identity_card,affiliate_city.first_shortened) as ci_completo_causa,
                'affiliates.first_name as primer_nombre_causahabiente',
                'affiliates.second_name as segundo_nombre_causahabiente',
                'affiliates.last_name as ap_paterno_causahabiente',
                'affiliates.mothers_last_name as ap_materno_causahabiente',
                'affiliates.surname_husband as ape_casada_causahabiente',
                'affiliates.birth_date as fecha_nacimiento',
                'affiliates.nua as codigo_nua_cua',
                'eco_com_city.name as regional',
                'procedure_modalities.name as tipo_de_prestacion',
                'eco_com_reception_types.name as reception_type',
                'eco_com_category.name as categoria',
                'eco_com_degree.name as grado',
                'pension_entities.name',
                'economic_complements.sub_total_rent as total_ganado_renta_pensión_SENASIR',
                'economic_complements.reimbursement as reintegro_SENASIR',
                'economic_complements.dignity_pension  as renta_dignidad_SENASIR',
                'economic_complements.aps_total_fsa as fraccion_saldo_acumulada_APS',
                'economic_complements.aps_total_cc as fraccion_compensacion_cotizaciones_APS',
                'economic_complements.aps_total_fs as fraccion_solidaria_vejez_APS',
                'economic_complements.total_rent as total_renta',
                'economic_complements.total_rent_calc as total_renta_neto',
                'economic_complements.seniority as antiguedad',
                'economic_complements.salary_reference as salario_referencial',
                'economic_complements.salary_quotable as salario_cotizable',
                'economic_complements.difference as diferencia',
                'economic_complements.total_amount_semester as total_semestre',
                'economic_complements.complementary_factor as factor_complementario',
                'economic_complements.total as total_complemento',
                'wf_states.first_shortened as ubicacion',
                'eco_com_modalities.name as tipo_beneficiario',
                'workflows.name as flujo'
            )
            // ->select(DB::raw(EconomicComplement::basic_info_colums() . $columns))
            ->get();
        $collect = collect([]);
        $observations_ids = ObservationType::where('description', 'Amortizable')->get()->pluck('id');
        foreach ($eco_coms as $e) {
            $observations = $e->observations->whereIn('id', $observations_ids);
            if ($observations->count() > 1) {
                $sw = true;
                $temp = collect([]);
                foreach ($observations as $o) {
                    if ($e->discount_types->where('id', Util::getDiscountId($o->id))->count() == 0) {
                        $sw = false;
                    }else{
                        $temp->push(Util::getDiscountId($o->id));
                    }
                }
                if ($sw) {
                    $e->observaciones = ObservationType::whereIn('id', $observations->pluck('id'))->pluck('name')->implode(' || ');
                    foreach ($e->discount_types->whereIn('id', $temp) as $dd) {
                        $e[Str::snake($dd->shortened)] = $dd->pivot->amount;
                    }
                    $collect->push($e);
                }
            }
        }
        // return view('exports.eco_com.more_observations', [
        //     'eco_coms' => $collect
        // ]);

        return $collect;
    }
    public function title(): string
    {
        return 'Multiples Obs';
    }
    public function headings(): array
    {
        $new_columns = [];
        $default = [
            'ID',
            'NUP',
            'Nro Tramite',
            "fecha_de_recepcion",
            'CI Beneficiario',
            'CI Exp BEN',
            // 'CI COMPLETO BEN',
            "Primer Nombre Beneficiario",
            "Segundo Nombre Beneficiario",
            "Paterno Beneficiario",
            "Materno Beneficiario",
            "Apellido casda Beneficiario",
            "Fecha Nacimiento Beneficiario",
            "Telefonos Beneficiario",
            "celulares Beneficiario",
            "oficialia Beneficiario",
            "libro Beneficiario",
            "partida Beneficiario",
            "fecha_matrimonio Beneficiario",
            "ci_causa",
            "exp_causa",
            // "ci_completo_causa",
            "primer_nombre_causahabiente",
            "segundo_nombre_causahabiente",
            "ap_paterno_causahabiente",
            "ap_materno_causahabiente",
            "ape_casada_causahabiente",
            "fecha_nacimiento",
            "codigo_nua_cua",
            "Regional",
            "Tipo de Prestacion",
            "Tipo de Recepcion",
            "Categoria",
            "Grado",
            "Ente Gestor",
            "total_ganado_renta_pensión_SENASIR",
            "reintegro_SENASIR",
            "renta_dignidad_SENASIR",
            "fraccion_saldo_acumulada_APS",
            "fraccion_compensacion_cotizaciones_APS",
            "fraccion_solidaria_vejez_APS",
            "total_renta",
            "total_renta_neto",
            "antiguedad",
            "salario_referencial",
            "salario_cotizable",
            "diferencia",
            "total_semestre",
            "factor_complementario",
            "total_complemento",
            "total_liquido_pagable",
            "Ubicacion",
            "tipoe_beneficiario",
            "flujo",
            "observaciones",
        ];
        return array_merge($default, $new_columns);
    }
}
