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
        $columns = '';
        switch ($this->report_type_id) {
            case 1:
                $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                    ->info()
                    ->beneficiary()
                    ->affiliateInfo()
                    // ->order()
                    ->select(DB::raw(EconomicComplement::basic_info_colums() . "," . EconomicComplement::basic_info_affiliates() . $columns))
                    ->get();
                break;
            case 2:
                $columns = ',economic_complements.aps_disability as concurrencia';
                $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                    ->info()
                    ->beneficiary()
                    ->affiliateInfo()
                    // ->order()
                    ->select(DB::raw(EconomicComplement::basic_info_colums() . "," . EconomicComplement::basic_info_affiliates() . $columns))
                    ->where('aps_disability', '>', 0)
                    ->get();
                break;
            case 3:
                $columns = '';
                $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                    ->info()
                    ->beneficiary()
                    ->affiliateInfo()
                    ->legalGuardianInfo()
                    // ->order()
                    ->select(DB::raw(EconomicComplement::basic_info_colums() . "," . EconomicComplement::basic_info_affiliates() . "," . EconomicComplement::basic_info_legal_guardian() . $columns))
                    ->has('eco_com_legal_guardian')
                    ->get();
                break;
            case 4:
                //! TODO VERIFICAR SI TIENE @ OBSERVACIONES
                $columns = ', observation_types.name as observaciones';
                $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                    ->info()
                    ->beneficiary()
                    ->affiliateInfo()
                    ->observationType()
                    // ->order()
                    ->select(DB::raw(EconomicComplement::basic_info_colums() . "," . EconomicComplement::basic_info_affiliates() . $columns))
                    ->whereHas('observations', function ($query) {
                        $query->whereIn('observation_type_id', $this->observation_type_ids);
                    })
                    ->get();
                break;
            case 6:
                $columns = ",wf_states.name as ubicacion, CASE WHEN economic_complements.inbox_state = true then 'Validado' ELSE 'Sin Validar' END as estado";
                $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                    ->info()
                    ->beneficiary()
                    ->affiliateInfo()
                    // ->order()
                    ->select(DB::raw(EconomicComplement::basic_info_colums() . "," . EconomicComplement::basic_info_affiliates() . $columns))
                    ->whereIn('economic_complements.wf_current_state_id', $this->wf_states_ids)
                    ->leftJoin('wf_states', 'economic_complements.wf_current_state_id', '=', 'wf_states.id')
                    ->where('economic_complements.inbox_state', false)
                    ->get();
                break;
            case 7:
                $columns = ",wf_states.name as ubicacion, CASE WHEN economic_complements.inbox_state = true then 'Validado' ELSE 'Sin Validar' END as estado";
                $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                    ->info()
                    ->beneficiary()
                    ->affiliateInfo()
                    // ->order()
                    ->select(DB::raw(EconomicComplement::basic_info_colums() . "," . EconomicComplement::basic_info_affiliates() . $columns))
                    ->whereIn('economic_complements.wf_current_state_id', $this->wf_states_ids)
                    ->leftJoin('wf_states', 'economic_complements.wf_current_state_id', '=', 'wf_states.id')
                    ->where('economic_complements.inbox_state', true)
                    ->get();
                break;
            case 8:
                $columns = ", economic_complements.deleted_at";
                $data = EconomicComplement::ecoComProcedure($this->eco_com_procedure_id)
                    ->info()
                    ->beneficiary()
                    ->affiliateInfo()
                    // ->order()
                    ->select(DB::raw(EconomicComplement::basic_info_colums() . "," . EconomicComplement::basic_info_affiliates() . $columns))
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
            case 2:
                $new_columns = [
                    'concurrencia'
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
            case 6:
            case 7:
                $new_columns = [
                    'ubicacion',
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
            'NRO',
            'Nro Tramite',
            'CI Beneficiario',
            'CI Exp',
            "Primer Nombre Beneficiario",
            "Segundo Nombre Beneficiario",
            "Paterno Beneficiario",
            "Materno Beneficiario",
            "Apellido casda Beneficiario",
            "Fecha Nacimiento Beneficiario",
            "Regional",
            "Grado",
            "Categoria",
            "Tipo de Prestacion",
            "Tipo",
            "NUP",
            "primer_nombre_causahabiente",
            "segundo_nombre_causahabiente",
            "ap_paterno_causahabiente",
            "ap_materno_causahabiente",
            "ape_casada_causahabiente",
            "fecha_nacimiento",
            "codigo_nua_cua",
        ];
        return array_merge($default, $new_columns);
    }
}
