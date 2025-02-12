<?php

namespace Muserpol\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Muserpol\Helpers\Util;
use Carbon\Carbon;
use Muserpol\Helpers\ID;

class EconomicComplementResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $observations = $this->observations()->where('enabled', false)->pluck('shortened')->unique();
        $discounts = $this->discount_types->map(function($e) {
            return $e['shortened'].': '.Util::formatMoney($e['pivot']['amount'], true);
        });
        $data = [
            [
                'key' => 'Beneficiario',
                'value' => $this->eco_com_beneficiary->fullName(),
            ], [
                'key' => 'C.I.',
                'value' => $this->eco_com_beneficiary->identity_card,
            ], [
                'key' => 'Semestre',
                'value' => $this->eco_com_procedure->fullName(),
            ], [
                'key' => 'Fecha de recepción',
                'value' => Util::getDateFormat($this->reception_date),
            ], [
                'key' => 'Nº de trámite',
                'value' => $this->code,
            ], [
                'key' => 'Tipo de prestación',
                'value' => $this->eco_com_modality->shortened,
            ], [
                'key' => 'Tipo de trámite',
                'value' => $this->eco_com_reception_type->name,
            ], 
            [
                'key' => 'Estado de trámite',
                'value' => $this->eco_com_state->name,
            ], 
            [
                'key' => 'Observaciones del trámite',
                'value' => $observations->count() > 0 ? $observations->values() : 'Ninguna',
            ]
        ];
        if($this->eco_com_state->eco_com_state_type_id == ID::ecoComStateType()->pagado)
        {
            if ($this->total) {
                $data[] = [
                    'key' => 'Total Comp. Económico',
                    'value' => Util::formatMoney($this->getOnlyTotalEcoCom(), true),
                ];
            }
            $data[] = [
                'key' => 'Descuentos',
                'value' => $discounts->count() > 0 ? $discounts : 'Ninguno',
            ];
            if ($this->base_wage) {
                $data[] = [
                    'key' => 'Líquido pagable',
                        'value' => Util::formatMoney($this->total, true),
                ];
            }
        }

        return [
            'id' => $this->id,
            'title' => mb_strtoupper($this->eco_com_procedure->semester) . ' SEMESTRE ' . Carbon::parse($this->eco_com_procedure->year)->year,
            'subtitle' => $this->eco_com_state->eco_com_state_type->name,
            'printable' => in_array($this->eco_com_state->eco_com_state_type->name, ['Creado', 'Enviado']) ? true : false,
            'display' => $data
        ];
    }
}
