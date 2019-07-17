<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Muserpol\Exports\PlanillaGeneral\EcoComTramitesLimpiosSheet;
use Muserpol\Models\ObservationType;
use Muserpol\Exports\PlanillaGeneral\EcoComAmortizationSheet;
use Muserpol\Exports\PlanillaGeneral\EcoComNoAmortizationSheet;
use Muserpol\Exports\PlanillaGeneral\EcoComMoreObservationSheet;
use Muserpol\Exports\PlanillaGeneral\EcoComMoreObservationNoAmortizableSheet;

class EcoComPlanillaGeneralReport implements WithMultipleSheets
{
    use Exportable;
    protected $eco_com_procedure_id;

    public function __construct(int $eco_com_procedure_id)
    {
        $this->eco_com_procedure_id = $eco_com_procedure_id;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new EcoComTramitesLimpiosSheet($this->eco_com_procedure_id);
        foreach (ObservationType::where('description', 'Amortizable')->get() as $o) {
            $sheets[] = new EcoComAmortizationSheet($this->eco_com_procedure_id, $o);
        }
        $sheets[] = new EcoComMoreObservationSheet($this->eco_com_procedure_id);
        foreach (ObservationType::where('description', 'Amortizable')->get() as $o) {
            $sheets[] = new EcoComNoAmortizationSheet($this->eco_com_procedure_id, $o);
        }
        $sheets[] = new EcoComMoreObservationNoAmortizableSheet($this->eco_com_procedure_id);
        return $sheets;
    }
}
