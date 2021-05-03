<?php

namespace Muserpol\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Muserpol\Helpers\Util;

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
        $observations = $this->observations()->pluck('shortened')->unique();
        $discounts = $this->discount_types->map(function($e) {
            return $e['shortened'].': '.Util::formatMoney($e['pivot']['amount'], true);
        });
        $data = [
            [
                'key' => 'Beneficiario',
                'value' => $this->eco_com_beneficiary->fullName(),
            ], [
                'key' => 'C.I.',
                'value' => $this->eco_com_beneficiary->ciWithExt(),
            ], [
                'key' => 'Semestre',
                'value' => $this->eco_com_procedure->fullName(),
            ], [
                'key' => 'Fecha de recepción',
                'value' => Util::getDateFormat($this->reception_date),
            ], [
            //     'key' => 'Nº de trámite',
            //     'value' => $this->code,
            // ], [
                'key' => 'Tipo de prestación',
                'value' => $this->eco_com_modality->shortened,
            ], [
                'key' => 'Tipo de trámite',
                'value' => $this->eco_com_reception_type->name,
            ], [
                'key' => 'Estado de trámite',
                'value' => $this->eco_com_state->name,
            ], [
                'key' => 'Observaciones',
                'value' => $observations->count() > 0 ? $observations->values() : 'Ninguna',
            ]
        ];
        if ($this->total) {
            $data[] = [
                'key' => 'Monto calculado',
                'value' => Util::formatMoney($this->total, true),
            ];
        }
        $data[] = [
            'key' => 'Descuentos',
            'value' => $discounts->count() > 0 ? $discounts : 'Ninguno',
        ];
        if ($this->base_wage) {
            $data[] = [
                'key' => 'Líquido pagable',
                    'value' => Util::formatMoney($this->base_wage->amount, true),
            ];
        }

        return [
            'id' => $this->id,
            'title' => $this->code,
            'subtitle' => $this->eco_com_state->eco_com_state_type->name,
            'display' => $data
        ];
    }
}
