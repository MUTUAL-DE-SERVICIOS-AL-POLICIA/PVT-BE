<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Muserpol\Models\Affiliate;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class EcoComPlanillaGeneralPagos implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $report_type_id;
    protected $eco_com_procedure_id;
    protected $date;
    public function __construct(int $report_type_id, int $id)
    {
        $this->report_type_id = $report_type_id;
        $this->eco_com_procedure_id = $id;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = null;
        switch ($this->report_type_id) {
            case 25:
                $data = DB::select("select nro, observacion, estado_observacion, affiliate_id, code, identity_card, first_name, second_name, last_name, mothers_last_name, surname_husband, regional, tipo_prestamo, tipo_recepcion, categoria, grado, ente_gestor, total_rent, total_rent_calc, seniority, salary_reference, salary_quotable, difference, total_amount_semester, complementary_factor, total_complement, amortizacion_prestamos, amortización_prestamo_estacional, amortizacion_reposicion,amortizacion_retencion_segun_juzgado, amortizacion_auxilio, amortizacion_cuentasxcobrar,total, ubicacion, tipo_beneficiario, estado, sigep_status, account_number, financialentities from public.planilla_general(".$this->eco_com_procedure_id.") where (eco_com_state_id = 16 and total>0) or (eco_com_state_id in (18,29))");
                break;
            case 26:
                $data = DB::select("select nro, observacion, estado_observacion, affiliate_id, code, identity_card, first_name, second_name, last_name, mothers_last_name, surname_husband, regional, tipo_prestamo, tipo_recepcion, categoria, grado, ente_gestor, total_rent, total_rent_calc, seniority, salary_reference, salary_quotable, difference, total_amount_semester, complementary_factor, total_complement, amortizacion_prestamos, amortización_prestamo_estacional, amortizacion_reposicion,amortizacion_retencion_segun_juzgado, amortizacion_auxilio, amortizacion_cuentasxcobrar,total, ubicacion, tipo_beneficiario, estado, sigep_status, account_number, financialentities from public.planilla_general(".$this->eco_com_procedure_id.") where eco_com_state_id = 16 and estado_observacion in ('','Subsanado') and sigep_status = 'ACTIVO' and account_number is not null and financialentities is not null and total>0");
                break;
            case 27:
                $data = DB::select("select nro, observacion, estado_observacion, affiliate_id, code, identity_card, first_name, second_name, last_name, mothers_last_name, surname_husband, regional, tipo_prestamo, tipo_recepcion, categoria, grado, ente_gestor, total_rent, total_rent_calc, seniority, salary_reference, salary_quotable, difference, total_amount_semester, complementary_factor, total_complement, amortizacion_prestamos, amortización_prestamo_estacional, amortizacion_reposicion,amortizacion_retencion_segun_juzgado, amortizacion_auxilio, amortizacion_cuentasxcobrar,total, ubicacion, tipo_beneficiario, estado, sigep_status, account_number, financialentities from public.planilla_general(".$this->eco_com_procedure_id.") where eco_com_state_id = 16 and estado_observacion in ('','Subsanado') and sigep_status <> 'ACTIVO' and total>0");
                break;
        }
        return collect($data);
    }
    public function headings(): array
    {
        $new_columns = [];

        $default = [
            'Nro ',
            'Observacion ',
            'Estado Observacion',
            'NUP ',
            'Nro Tramite ',
            'CI Beneficiario ',
            'Primer nombre ',
            'Segundo nombre ',
            'Paterno ',
            'Materno ',
            'Apellido casada(o) ',
            'Regional ',
            'Tipo prestacion ',
            'Tipo Recepcion ',
            'Categoria ',
            'Grado ',
            'Ente gestor ',
            'Total renta ',
            'Total renta neto ',
            'Antiguedad ',
            'Salario referencia ',
            'Salario cotizable ',
            'Diferencia ',
            'Total semestre ',
            'Factor complementario ',
            'Total Complemento ',
            'Amortizacion por prestamo en mora ',
            'Amortización por préstamo Estacional ',
            'Amortizacion por reposicion de fondos ',
            'Amortización retención según juzgado',
            'Descuento por auxilio mortuorio ',
            'Amortizacion por cuentas por cobrar ',
            'Total liquido pagable ',
            'Ubicacion ',
            'Tipo beneficiario ',
            'Estado ',
            'Estado sigep',
            'Numero cuenta',
            'Entidad financiera',
        ];
        return array_merge($default, $new_columns);
    }
}
