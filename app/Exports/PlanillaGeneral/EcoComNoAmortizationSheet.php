<?php

namespace Muserpol\Exports\PlanillaGeneral;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use DB;
use Muserpol\Models\ObservationType;
use Muserpol\Helpers\Util;

class EcoComNoAmortizationSheet implements FromQuery, WithTitle, WithHeadings, ShouldAutoSize
{
    protected $eco_com_procedure_id;
    protected $observation_type;
    public function __construct($eco_com_procedure_id,$observation_type)
    {
        $this->eco_com_procedure_id = $eco_com_procedure_id;
        $this->observation_type = $observation_type;
    }
    public function query()
    {
        $columns = '';
        return EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
            ->info()
            ->beneficiary()
            ->affiliateInfo()
            ->wfstates()
            ->where('economic_complements.wf_current_state_id', 3)
            ->whereIn('economic_complements.eco_com_state_id', [16,18])
            ->where('economic_complements.total', '>=', 0)
            ->whereNotIn('economic_complements.id', function ($query) {
                $query->select('observables.observable_id')
                    ->from('observables')
                    ->where('observables.observable_type', 'economic_complements')
                    ->whereIn('observables.observation_type_id', ObservationType::all()->except($this->observation_type->id)->pluck('id'))
                    ->whereNull('observables.deleted_at');
            })
            ->whereIn('economic_complements.id', function ($query) {
                $query->select('observables.observable_id')
                    ->from('observables')
                    ->where('observables.observable_type', 'economic_complements')
                    ->whereIn('observables.observation_type_id', [$this->observation_type->id])
                    ->whereNull('observables.deleted_at');
            })
            ->whereNotIn('economic_complements.id', function ($query) {
                $query->select('discount_type_economic_complement.economic_complement_id')
                    ->from('discount_type_economic_complement')
                    ->where('discount_type_economic_complement.discount_type_id', Util::getDiscountId($this->observation_type->id));
            })
            ->select(DB::raw(EconomicComplement::basic_info_colums() . $columns));
    }
    public function title(): string
    {
        return $this->observation_type->shortened . 'No Amort';
    }
    public function headings(): array
    {
        $new_columns = [];
        $default = [
            'NRO',
            'NUP',
            'Nro Tramite',
            "fecha_de_recepcion",
            'CI Beneficiario',
            'CI Exp BEN',
            'CI COMPLETO BEN',
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
            "ci_completo_causa",
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
        ];
        return array_merge($default, $new_columns);
    }
}
