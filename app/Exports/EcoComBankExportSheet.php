<?php
namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


use DB;
use Muserpol\Models\City;
use Muserpol\Helpers\Util;

class EcoComBankExportSheet implements FromCollection, WithTitle, WithHeadings,WithColumnFormatting, ShouldAutoSize
{
    private $eco_com_procedure_id;
    private $change_state;

    public function __construct(int $id,$change_state)
    {
        $this->eco_com_procedure_id = $id;
        $this->change_state = $change_state;
    }

    /**
     * @return Builder
     */
    public function collection()
    {
        return Util::getEconomicComplementSendToBank($this->eco_com_procedure_id, $this->change_state)['result'];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Detalle';
    }
    public function headings(): array
    {
        return [
            'Numero de boleta', // nro 12345567...
            'Monto', //total 1512151.23
            'Cuenta del abonado', //vacio
            'Numero de documento', //ci
            'Lugar', //ext toBank , si es naturalizado colocar lp por defecto
            'TipoDoc', // CI -> numero sin extension, //CIE -> con extension, // PE -> si en naturalizado
            'Primer apellido',
            'Segundo apellido',
            'Tercer apellido', // apellido de casada
            'Primer nombre', // si tienes 3 nombres 1, 2, si tiene 4 es 2,2
            'Segundo nombre',
            'Año de pago', //2019
            'Mes de pago', // junio mes de pago
            'Descripcion 1',
            'Descripcion 2', //2307 por defecto
            'Descripcion 3' // affiliate_id
        ];
    }
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_GENERAL,
            'D' => NumberFormat::FORMAT_GENERAL,
            'G' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

}
