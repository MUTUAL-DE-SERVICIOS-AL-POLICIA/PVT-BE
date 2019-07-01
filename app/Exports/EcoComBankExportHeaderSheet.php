<?php
namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromArray;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Helpers\Util;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;



class EcoComBankExportHeaderSheet implements FromArray, WithTitle, WithHeadings, WithColumnFormatting,  ShouldAutoSize
{
    private $eco_com_procedure_id;
    private $total_amount;
    private $total_eco_coms;

    public function __construct(int $id)
    {
        $this->eco_com_procedure_id = $id;
    }

    public function getProcedure()
    {
        return EcoComProcedure::find($this->eco_com_procedure_id);
    }
    public function getEcoComs()
    {
        $results  = Util::getEconomicComplementSendToBank($this->eco_com_procedure_id);
        $this->total_amount = $results['total_amount'];
        $this->total_eco_coms = $results['total_eco_coms'];
    }

    public function array(): array
    {
        $this->getEcoComs();
        return [
            [
                132,
                now()->format('Ymd'),
                1,
                'BONO',
                "11111111111111",
                $this->total_eco_coms,
                $this->total_amount,
                'Bs',
                'PAGO DE COMPLEMENTO ECONOMICO',
                'B',
                132,
                $this->getProcedure()->getSemesterText(),
                'GESTION ' . $this->getProcedure()->getYear()
            ],
        ];
    }
    public function title(): string
    {
        return 'Cabecera';
    }
    public function headings(): array
    {
        return [
            'Codigo entidad',
            'Fecha Carga',
            'Nro Carga',
            'Servicio',
            'Numero Cta',
            'Cantidad boletas',
            'Suma total',
            'Moneda',
            'Concepto de pago',
            'Tipo Abono',
            'Concepto',
            'Descripcion 2',
            'Descripcion 3',
        ];
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'K' => NumberFormat::FORMAT_TEXT,
        ];
    }

}
