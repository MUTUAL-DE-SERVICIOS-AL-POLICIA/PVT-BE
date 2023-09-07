<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\EconomicComplement\EcoComState;
use DB;
class EcoComStateReport implements WithMultipleSheets
{
    use Exportable;
    protected $eco_com_procedure_id;
    protected $wf_states_ids;

    public function __construct(int $eco_com_procedure_id, $wf_states_ids)
    {
        $this->eco_com_procedure_id = $eco_com_procedure_id;
        $this->wf_states_ids = $wf_states_ids;
    }
    public function sheets(): array
    {
        $sheets = [];
        $eco_com_states = EcoComState::all();
        $eco_coms = EconomicComplement::with('eco_com_state')
            ->select(
                'economic_complements.id as id',
                'economic_complements.affiliate_id as NUP',
                'economic_complements.code as eco_com_code',
                'economic_complements.reception_date as fecha_recepcion',
                'beneficiary.identity_card as ci_ben',
                'beneficiary.first_name as primer_nombre_ben',
                'beneficiary.second_name as segundo_nombre_ben',
                'beneficiary.last_name as apellido_paterno_ben',
                'beneficiary.mothers_last_name as apellido_materno_ben',
                'beneficiary.surname_husband as apellido_de_casado_ben',
                'beneficiary.birth_date as fecha_nac_ben',
                'beneficiary.phone_number as telefonos_ben',
                'beneficiary.cell_phone_number as celulares_ben',
                'affiliates.identity_card as ci_causa',
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
                DB::raw(
                    'round(economic_complements.total_amount_semester * round(economic_complements.complementary_factor/100, 3), 2) as total_complemento'
                ),
                'economic_complements.total as total_liquido_pagable',
                'wf_states.first_shortened as ubicacion',
                'eco_com_modalities.name as tipo_beneficiario',
                'workflows.name as flujo',
                'eco_com_state_id as eco_com_state_id',
                
            )
            ->info()
            ->beneficiary()
            ->affiliateInfo()
            ->wfstates()
            ->ecoComProcedure($this->eco_com_procedure_id)
            ->whereIn('economic_complements.wf_current_state_id', $this->wf_states_ids)
            ->get();
        foreach ($eco_com_states as $e) {
            $sheets[] = new EcoComStateSheet($e, $eco_coms);
        }
        return $sheets;
    }
}
