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
        return [
            'id' => $this->id,
            'affiliate_id' => $this->affiliate_id,
            'display' => [
                [
                    'key' => 'Beneficiario',
                    'value' => $this->eco_com_beneficiary->fullName(),
                    'array' => false
                ], [
                    'key' => 'C.I.',
                    'value' => $this->eco_com_beneficiary->ciWithExt(),
                    'array' => false
                ], [
                    'key' => 'Semestre',
                    'value' => $this->eco_com_procedure->fullName(),
                    'array' => false
                ], [
                    'key' => 'Fecha de recepción',
                    'value' => Util::getDateFormat($this->reception_date),
                    'array' => false
                ], [
                    'key' => 'Nº de trámite',
                    'value' => $this->code,
                    'array' => false
                ], [
                    'key' => 'Tipo de prestación',
                    'value' => $this->eco_com_modality->shortened,
                    'array' => false
                ], [
                    'key' => 'Tipo de trámite',
                    'value' => $this->eco_com_reception_type->name,
                    'array' => false
                ], [
                    'key' => 'Estado de trámite',
                    'value' => $this->eco_com_state->name,
                    'array' => false
                ], [
                    'key' => 'Observaciones',
                    'value' => $this->observations()->pluck('name'),
                    'array' => true
                ], [
                    'key' => 'Total pagado',
                    'value' => Util::formatMoney($this->total, true),
                    'array' => false
                ], [
                    // TODO: verificar columna en BD
                    'key' => 'Descuentos',
                    'value' => [
                        Util::formatMoney($this->seniority, true).' Descuento 1',
                        Util::formatMoney($this->seniority, true).' Descuento X'
                    ],
                    'array' => true
                ], [
                    'key' => 'Líquido pagable',
                    'value' => Util::formatMoney($this->base_wage->amount, true),
                    'array' => false
                ]
            ]
        ];
    }
}
