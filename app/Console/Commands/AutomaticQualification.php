<?php

namespace Muserpol\Console\Commands;

use Illuminate\Console\Command;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\EconomicComplement\EcoComRent;
use Muserpol\Models\EconomicComplement\EconomicComplement;

class AutomaticQualification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automatic:qualification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        auth()->loginUsingId(9);
        $year = $this->ask('Enter the year');
        $semester = $this->ask('Enter the semester');
        $eco_com_procedure = EcoComProcedure::whereYear('year', '=', $year)
            ->where('semester', '=', $semester)
            ->first();
        $eco_coms = EconomicComplement::with('eco_com_state')->where('eco_com_procedure_id', $eco_com_procedure->id)
            ->where('total_rent', '>', 0)
            // ->whereNull('total')
            ->get();
        $count_all = 0;

        foreach ($eco_coms as $e) {
            if (($e->eco_com_state->eco_com_state_type_id != 1 || $e->eco_com_state->eco_com_state_type_id != 6)) {
                $count_all++;
                $e->qualify();
            }
        }
        $this->info($count_all);
    }
}
