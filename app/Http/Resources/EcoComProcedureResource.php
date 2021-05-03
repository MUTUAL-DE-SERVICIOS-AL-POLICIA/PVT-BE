<?php

namespace Muserpol\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Muserpol\Helpers\Util;
use Carbon\Carbon;

class EcoComProcedureResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $year = Carbon::parse($this->year)->year;
        $data = [];
        $last_procedure = $request->affiliate->economic_complements()->whereHas('eco_com_procedure', function($q) { $q->orderBy('year')->orderBy('normal_start_date'); })->latest()->first();

        if ($last_procedure) {
            $data = [
                [
                    'key' => 'Beneficiario',
                    'value' => $last_procedure->eco_com_beneficiary->fullName(),
                ], [
                    'key' => 'C.I.',
                    'value' => $last_procedure->eco_com_beneficiary->ciWithExt(),
                ]
            ];
        }
        $data[] = [
            'key' => 'Semestre',
            'value' => $this->semester.'/'.$year,
        ];
        $data[] = [
            'key' => 'Fecha de recepción',
            'value' => Util::getDateFormat(Carbon::now()->toDateString(), true),
        ];
        $data[] = [
            'key' => 'Tipo de prestación',
            'value' => $last_procedure->eco_com_modality->shortened,
        ];

        return [
            'id' => $this->id,
            'title' => 'Nuevo trámite',
            'subtitle' => '',
            'display' => $data
        ];
    }
}
