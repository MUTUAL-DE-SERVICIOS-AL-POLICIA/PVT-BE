<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use DB;

class EcoComReports implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $eco_com_procedure_id;
    protected $report_type_id;
    protected $observation_type_ids;
    protected $wf_states_ids;
    public function __construct($eco_com_procedure_id, $report_type_id, $observation_type_ids, $wf_states_ids)
    {
        $this->eco_com_procedure_id = $eco_com_procedure_id;
        $this->report_type_id = $report_type_id;
        $this->observation_type_ids = $observation_type_ids;
        $this->wf_states_ids = $wf_states_ids;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = null;
        $columns = ",". EconomicComplement::basic_info_discount();
        switch ($this->report_type_id) {
            case 1:
                $columns_add = ",eco_com_states.name as Estado_de_tramite,
                CASE WHEN  affiliate_devices.enrolled = true then 'Enrolado' ELSE 'No Enrolado' END as enrolled,
                CASE WHEN  affiliate_devices.verified = true then 'Validado' ELSE 'Sin Validar' END as verified,
                (CASE WHEN  (affiliate_tokens.api_token is not null and affiliate_tokens.firebase_token is not null) then 'Habilitado' ELSE 'No Habilitado' END) as notification,
                MAX(COALESCE(
                    CASE
                        WHEN wf_records.wf_state_id = 3 AND wf_records.message ilike '%validó%'
                        THEN users.username
                        ELSE ''
                    END,
                    ''
                )) AS mensaje,
                eco_com_updated_pensions.rent_type as AM_tipo_renta,
                eco_com_updated_pensions.aps_total_fsa as AM_fraccion_saldo_acumulada_APS,
                eco_com_updated_pensions.aps_total_cc as AM_fraccion_compensacion_cotizaciones_APS,
                eco_com_updated_pensions.aps_total_fs as AM_fraccion_solidaria_vejez_APS,
                eco_com_updated_pensions.aps_disability as AM_pension_de_invalidez,
                eco_com_updated_pensions.aps_total_death as AM_pension_por_muerte,
                eco_com_updated_pensions.total_rent as AM_total_renta_AM";
                $data = EconomicComplement::where("economic_complements.eco_com_procedure_id",$this->eco_com_procedure_id)
                    ->groupBy("economic_complements.affiliate_id",
                    "economic_complements.code",
                    "economic_complements.reception_date",
                    "beneficiary.identity_card",
                    "beneficiary.first_name",
                    "beneficiary.second_name",
                    "beneficiary.last_name",
                    "beneficiary.mothers_last_name",
                    "beneficiary.surname_husband",
                    "beneficiary.birth_date",
                    "beneficiary.phone_number",
                    "beneficiary.cell_phone_number",
                    "beneficiary.gender",
                    "affiliates.identity_card",
                    "affiliates.first_name",
                    "affiliates.second_name",
                    "affiliates.last_name",
                    "affiliates.mothers_last_name",
                    "affiliates.surname_husband",
                    "affiliates.birth_date",
                    "affiliates.service_years",
                    "affiliates.service_months",
                    "affiliates.nua",
                    "affiliates.sigep_status",
                    "financial_entities.name",
                    "affiliates.account_number",
                    "eco_com_city.name",
                    "procedure_modalities.name",
                    "eco_com_reception_types.name",
                    "eco_com_category.name",
                    "eco_com_degree.name",
                    "pension_entities.name",
                    "economic_complements.sub_total_rent",
                    "economic_complements.reimbursement",
                    "economic_complements.dignity_pension",
                    "economic_complements.aps_total_fsa",
                    "economic_complements.aps_total_cc",
                    "economic_complements.aps_total_fs",
                    "economic_complements.aps_disability",
                    "economic_complements.aps_total_death",
                    "economic_complements.total_rent",
                    "economic_complements.total_rent_calc",
                    "economic_complements.seniority",
                    "economic_complements.salary_reference",
                    "economic_complements.salary_quotable",
                    "economic_complements.difference",
                    "economic_complements.total_amount_semester",
                    "economic_complements.complementary_factor",
                    "economic_complements.total",
                    "wf_states.first_shortened",
                    "eco_com_modalities.name",
                    "workflows.name",
                    "eco_com_user.id",
                    "eco_com_states.name",
                    "affiliate_devices.enrolled",
                    "affiliate_devices.verified",
                    "affiliate_tokens.api_token",
                    "affiliate_tokens.firebase_token",
                    "eco_com_updated_pensions.rent_type",
                    "eco_com_updated_pensions.aps_total_fsa",
                    "eco_com_updated_pensions.aps_total_cc",
                    "eco_com_updated_pensions.aps_total_fs",
                    "eco_com_updated_pensions.aps_disability",
                    "eco_com_updated_pensions.aps_total_death",
                    "eco_com_updated_pensions.total_rent"
                    )
                    ->info()
                    ->beneficiary()
                    ->affiliateInfo()
                    ->wfstates()
                    ->ecocomstates()
                    ->affiliatetokens()
                    ->wfrecords()
                    ->updatedpension()
                    // ->order()
                    ->select(DB::raw(EconomicComplement::basic_info_colums().$columns.$columns_add))
                    ->get();
                break;
            case 2:
                $columns = ',economic_complements.aps_disability as monto_invalidez,economic_complements.aps_total_death as monto_muerte';
                $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                    ->info()
                    ->beneficiary()
                    ->affiliateInfo()
                    ->wfstates()
                    // ->order()
                    ->select(DB::raw(EconomicComplement::basic_info_colums(). $columns))
                    ->where(function ($query) {
                        $query->where('aps_disability', '>', 0)
                              ->orWhere('aps_total_death', '>', 0);
                    })
                    ->whereIn('economic_complements.wf_current_state_id', $this->wf_states_ids)
                    ->get();
                break;
            case 3:
                $columns = '';
                $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                    ->info()
                    ->beneficiary()
                    ->affiliateInfo()
                    ->wfstates()
                    ->legalGuardianInfo()
                    // ->order()
                    ->whereIn('economic_complements.wf_current_state_id', $this->wf_states_ids)
                    ->select(DB::raw(EconomicComplement::basic_info_colums() . "," . EconomicComplement::basic_info_legal_guardian() . $columns))
                    ->has('eco_com_legal_guardian')
                    ->get();
                break;
            case 4:
                //! TODO VERIFICAR SI TIENE @ OBSERVACIONES
                // $columns = ', observation_types.name as observaciones';
                $columns = '';
                $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                    ->info()
                    ->beneficiary()
                    ->affiliateInfo()
                    ->wfstates()
                    ->observationType()
                    // ->order()
                    ->select(DB::raw(EconomicComplement::basic_info_colums()  . $columns))
                    ->whereHas('observations', function ($query) {
                        $query->whereIn('observation_type_id', $this->observation_type_ids);
                    })
                    ->whereIn('economic_complements.wf_current_state_id', $this->wf_states_ids)
                    ->get();
                break;
            case 5:
                    $columns = '';
                    $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                        ->info()
                        ->beneficiary()
                        ->affiliateInfo()
                        ->wfstates()
                        ->spouseInfo()
                        // ->order()
                        ->select(DB::raw(EconomicComplement::basic_info_colums() . "," . EconomicComplement::basic_info_spouse() . $columns))
                        ->where('economic_complements.is_paid',true)
                        //->has('eco_com_legal_guardian')
                        ->get();
                        
                break;
            case 6:
                $columns = ",wf_states.name as ubicacion, CASE WHEN economic_complements.inbox_state = true then 'Validado' ELSE 'Sin Validar' END as estado";
                $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                    ->info()
                    ->beneficiary()
                    ->affiliateInfo()
                    ->wfstates()
                    // ->order()
                    ->select(DB::raw(EconomicComplement::basic_info_colums() . $columns))
                    ->whereIn('economic_complements.wf_current_state_id', $this->wf_states_ids)
                    ->where('economic_complements.inbox_state', false)
                    ->get();
                break;
            case 7:
                $columns = ",wf_states.name as ubicacion, CASE WHEN economic_complements.inbox_state = true then 'Validado' ELSE 'Sin Validar' END as estado";
                $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                    ->info()
                    ->beneficiary()
                    ->affiliateInfo()
                    ->wfstates()
                    // ->order()
                    ->select(DB::raw(EconomicComplement::basic_info_colums() . $columns))
                    ->whereIn('economic_complements.wf_current_state_id', $this->wf_states_ids)
                    ->where('economic_complements.inbox_state', true)
                    ->get();
                break;
            case 8:
                $recordableType = (new EconomicComplement())->getMorphClass();
                $columns = ", economic_complements.deleted_at";
                $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                    ->infoDelete()
                    ->beneficiary()
                    ->affiliateInfo()
                    ->wfstates()
                    ->select(DB::raw(EconomicComplement::basic_info_colums() . $columns))
                    ->onlyTrashed()
                    ->get();
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
            case 1:
                $new_columns = [
                    "Amortización_Préstamos_en_Mora ",
                    "Amortización_Préstamo_Estacional ",
                    "Amortización_Reposición_de_Fondos",
                    "Amortización_Auxilio_Mortuorio",
                    "Amortización_Cuentas_por_cobrar",
                    "Amortizacion_Retencion_segun_juzgado",
                    "Estado_de_tramite",
                    "Enrolamiento",
                    "Contraste C.I",
                    "Notificación",
                    "Validado por",
                    "AM_tipo_renta",
                    "AM_fraccion_saldo_acumulada_APS",
                    "AM_fraccion_compensacion_cotizaciones_APS",
                    "AM_fraccion_solidaria_vejez_APS",
                    "AM_pension_de_invalidez",
                    "AM_pension_por_muerte",
                    "AM_total_renta"
                ];
                break;
            case 2:
                $new_columns = [
                    'MONTO INVALIDEZ',
                    'MONTO MUERTE',
                ];
                break;
            case 3:
                $new_columns = [
                    "primer_nombre_apoderado",
                    "segundo_nombre_apoderado",
                    "ap_paterno_apoderado", "ap_materno_apoderado", "ape_casada_apoderado",
                    "ci_apoderado",
                    "ci_exp_apoderado",
                    "tipo"
                ];
                break;
            case 4:
                $new_columns = [
                    'observaciones'
                ];
                break;
            case 5:
                $new_columns = [
                    "ci_conyugue",
                    "ci_exp_conyugue",
                    "primer_nombre_conyugue",
                    "segundo_nombre_conyugue",
                    "ap_paterno_conyugue",
                    "ap_materno_conyugue",
                    "ape_casada_conyugue",
                ];
                break;
            case 6:
            case 7:
                $new_columns = [
                    'estado'
                ];
                break;
            case 8:
                $new_columns = [
                    'Fecha Eliminado'
                ];
                break;
            default:
                # code...
                break;
        }
        $default = [
            "NRO",
            "NUP",
            "Nro Tramite",
            "fecha_de_recepcion",
            "usuario",
            "CI Beneficiario",
            "Primer Nombre Beneficiario",
            "Segundo Nombre Beneficiario",
            "Paterno Beneficiario",
            "Materno Beneficiario",
            "Apellido casada Beneficiario",
            "Fecha Nacimiento Beneficiario",
            "Telefonos Beneficiario",
            "celulares Beneficiario",
            "Genero Beneficiario",
            "ci_causa",
            "primer_nombre_causahabiente",
            "segundo_nombre_causahabiente",
            "ap_paterno_causahabiente",
            "ap_materno_causahabiente",
            "ape_casada_causahabiente",
            "fecha_nacimiento",
            "Anios_de_servicio",
            "Meses_de_servicio",
            "codigo_nua_cua",
            "Estado_sigep",
            "Entidad_financiera",
            "Numero_de_cuenta",
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
            "pension_de_invalidez",
            "pension_por_muerte",
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
            "tipo_beneficiario",
            "flujo",
        ];
        return array_merge($default, $new_columns);
    }
}
