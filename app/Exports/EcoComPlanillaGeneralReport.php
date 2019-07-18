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
use Muserpol\Exports\PlanillaGeneral\EcoComOneObservationSheet;
use Muserpol\Exports\PlanillaGeneral\EcoComOneStateSheet;
use Muserpol\Models\EconomicComplement\EcoComState;

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

        foreach (ObservationType::whereIn('id', [22,39])->get() as $o) {
            $sheets[] = new EcoComOneObservationSheet($this->eco_com_procedure_id, $o);
        }
        foreach (EcoComState::whereIn('id', [17,18])->get() as $o) {
            $sheets[] = new EcoComOneStateSheet($this->eco_com_procedure_id, $o);
        }
        foreach (ObservationType::where('description', 'Amortizable')->get() as $o) {
            $sheets[] = new EcoComNoAmortizationSheet($this->eco_com_procedure_id, $o);
        }
        $sheets[] = new EcoComMoreObservationNoAmortizableSheet($this->eco_com_procedure_id);
        return $sheets;
    }
}
