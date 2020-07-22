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
    protected $change_state;
    public function __construct(int $eco_com_procedure_id, $change_state = false)
    {
        $this->eco_com_procedure_id = $eco_com_procedure_id;
        $this->change_state = $change_state;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new EcoComTramitesLimpiosSheet($this->eco_com_procedure_id, $this->change_state);

        foreach (ObservationType::where('description', 'Amortizable')->get() as $o) {
            $sheets[] = new EcoComAmortizationSheet($this->eco_com_procedure_id, $o, $this->change_state);
        }

        $sheets[] = new EcoComMoreObservationSheet($this->eco_com_procedure_id, $this->change_state);

        foreach (ObservationType::whereIn('id', [22,39])->get() as $o) {
            $sheets[] = new EcoComOneObservationSheet($this->eco_com_procedure_id, $o, $this->change_state);
        }

        foreach (EcoComState::whereIn('id', [17,18])->get() as $o) {
            $sheets[] = new EcoComOneStateSheet($this->eco_com_procedure_id, $o, $this->change_state);
        }

        foreach (ObservationType::where('description', 'Amortizable')->get() as $o) {
            $sheets[] = new EcoComNoAmortizationSheet($this->eco_com_procedure_id, $o, $this->change_state);
        }

        $sheets[] = new EcoComMoreObservationNoAmortizableSheet($this->eco_com_procedure_id, $this->change_state);
        
        return $sheets;
    }
}
