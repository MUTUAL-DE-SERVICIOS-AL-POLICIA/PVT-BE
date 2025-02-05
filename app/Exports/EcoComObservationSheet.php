<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use Muserpol\Models\ObservationType;
use Illuminate\Support\Str;

class EcoComObservationSheet  implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    private $observation_type;
    private $eco_coms;

    public function __construct(ObservationType $observation_type, Collection $eco_coms)
    {
        $this->observation_type = $observation_type;
        $this->eco_coms = $eco_coms;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = collect([]);
        foreach ($this->eco_coms as $e) {
            $observation = $e->observations->where('id', $this->observation_type->id)->first();
            // if ($e->observations->contains($this->observation_type->id)) {
            if ($observation) {
                $e->observation_name = $this->observation_type->name;
                $e->observation_state = $observation->pivot->enabled ? 'Subsanado' : 'No subsanado';
                $e->observation_deleted_at = $observation->pivot->deleted_at;
                $data->push($e);
            }
        }
        return $data;
    }
    public function title(): string
    {
        // return Str::limit(collect(explode('-', $this->observation_type->name))->last(), 25);
        return Str::limit(collect(explode('-', $this->observation_type->shortened))->last(), 25);
    }
    public function headings(): array
    {
        $new_columns = ['Nombre observacion', 'Estado observacion','Fecha Eliminacion'];
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
            // "Entidad_financiera",
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
        ];
        return array_merge($default, $new_columns);
    }
}
