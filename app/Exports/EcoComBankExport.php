<?php
namespace Muserpol\Exports;

use Muserpol\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use DB;

class EcoComBankExport implements WithColumnFormatting, WithHeadings, ShouldAutoSize, WithMultipleSheets
{
    protected $eco_com_procedure_id;
    protected $change_state;
    public function __construct(int $id,$change_state)
    {
        $this->eco_com_procedure_id = $id;
        $this->change_state = $change_state;
    }

    // public function collection()
    // {
    //     
    // }
    public function sheets(): array
    {
        $sheets = [];

        // for ($month = 1; $month <= 2; $month++) {
        $sheets[] = new EcoComBankExportHeaderSheet($this->eco_com_procedure_id, $this->change_state);
        $sheets[] = new EcoComBankExportSheet($this->eco_com_procedure_id, $this->change_state);
        // }

        return $sheets;
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
            // 'D' => '#,##0.00', //1.000,10 (depende de windows
            // 'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,  //1.000,10
            // 'M' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            // 'W' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        ];
    }
}
