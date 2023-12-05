<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Muserpol\Models\EconomicComplement\EcoComState;

class EcoComStateSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    private $eco_com_state;
    private $eco_coms;
    public function __construct(EcoComState $eco_com_state, Collection $eco_coms)
    {
        $this->eco_com_state = $eco_com_state;
        $this->eco_coms = $eco_coms;
    }
    public function collection()
    {
        $data = collect([]);
        foreach ($this->eco_coms as $e) {

            if ($e->eco_com_state_id ==  $this->eco_com_state->id) {
                $e->eco_com_state_name = $this->eco_com_state->name;
                // $e->observaciones = $e->observations->pluck('name')->implode(' || ');
                $data->push($e);
            }
        }
        return $data;
    }
    public function title(): string
    {
        return Str::limit(collect(explode('-', $this->eco_com_state->name))->last(), 25);
    }
    public function headings(): array
    {
        $new_columns = ['Nombre observacion', 'Estado observacion'];
        $default = [
            'ID',
            'NUP',
            'Nro Tramite',
            "fecha_de_recepcion",
            'CI Beneficiario',
            // 'CI COMPLETO BEN',
            "Primer Nombre Beneficiario",
            "Segundo Nombre Beneficiario",
            "Paterno Beneficiario",
            "Materno Beneficiario",
            "Apellido casda Beneficiario",
            "Fecha Nacimiento Beneficiario",
            "Telefonos Beneficiario",
            "celulares Beneficiario",
            "ci_causa",
            // "ci_completo_causa",
            "primer_nombre_causahabiente",
            "segundo_nombre_causahabiente",
            "ap_paterno_causahabiente",
            "ap_materno_causahabiente",
            "ape_casada_causahabiente",
            "fecha_nacimiento",
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
            "estado",
        ];
        return array_merge($default, $new_columns);
    }
}
