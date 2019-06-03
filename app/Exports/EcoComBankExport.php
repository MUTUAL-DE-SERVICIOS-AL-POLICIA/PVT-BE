<?php
namespace Muserpol\Exports;

use Muserpol\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use DB;
class EcoComBankExport implements FromCollection, WithColumnFormatting, WithHeadings, ShouldAutoSize
{
    public function __construct(int $id)
    {
        $this->eco_com_procedure_id = $id;
    }

    public function collection()
    {
        $eco_com_procedure = EcoComProcedure::find($this->eco_com_procedure_id);
        $ecos = EconomicComplement::with([
            'degree',
            'category',
            'eco_com_modality',
            'city',
            'observations',
            'eco_com_beneficiary.city_identity_card',
            'eco_com_legal_guardian',
            'affiliate.spouse',
        ])
            ->ecoComProcedure($this->eco_com_procedure_id) // procedure_id
            ->NotHasEcoComState(1, 3, 6) // q el Trámite no tenga estado de pagado, excluido o enviado al banco
            ->workflow(1, 2, 3) // los 3 workflows
            ->wfState(3) // Area tecnica
            ->inboxState(true) // Trámites en la segunda bandeja
            // ->leftJoin('observables')
            ->city() // eco_com_city
            ->beneficiary() // beneficiary
            ->select('economic_complements.*')
            ->where('economic_complements.total', '>', 0)
            ->whereRaw("not exists(SELECT observables.observable_id FROM observables
      WHERE economic_complements.id = observables.observable_id AND observables.observable_type like 'economic_complements' AND
        observables.observation_type_id IN (1, 2, 13, 22) AND
        observables.enabled = FALSE AND observables.deleted_at is null)")
            ->get();
        // logger(DB::getQueryLog());
        $result = collect([]);
        foreach ($ecos as $e) {
            $result->push([
                $e->city->second_shortened, //'DEPARTAMENTO'
                $e->eco_com_beneficiary->ciWithExt(), //'IDENTIFICACION'
                $e->eco_com_beneficiary->fullName(), //'NOMBRE_Y_APELLIDO'
                $e->total, //'IMPORTE_A_PAGAR'
                "1", //'MONEDA_DEL_IMPORTE'
                $e->eco_com_modality->shortened . " - " . $e->degree->shortened . " - " . $e->category->name, //'DESCRIPCION_1'
                $e->affiliate_id, //'DESCRIPCION_2'
                $eco_com_procedure->getNameSendBank(), //'DESCRIPCION_3'
            ]);
        }
        // logger(DB::getQueryLog());
        logger(collect(DB::getQueryLog())->pluck('query'));

        return $result;
    }
    public function headings(): array
    {
        return [
            'DEPARTAMENTO',
            'IDENTIFICACION',
            'NOMBRE_Y_APELLIDO',
            'IMPORTE_A_PAGAR',
            'MONEDA_DEL_IMPORTE',
            'DESCRIPCION_1',
            'DESCRIPCION_2',
            'DESCRIPCION_3',
        ];
    }
    public function columnFormats(): array
    {
        return [
            'D' => '#,##0.00', //1.000,10 (depende de windows
            // 'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,  //1.000,10
            // 'M' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            // 'W' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        ];
    }
}
