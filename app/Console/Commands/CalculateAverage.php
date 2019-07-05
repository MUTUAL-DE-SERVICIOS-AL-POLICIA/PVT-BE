<?php

namespace Muserpol\Console\Commands;

use Illuminate\Console\Command;
use Muserpol\Models\EconomicComplement\EcoComRent;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use DB;
use Carbon\Carbon;
use Muserpol\Models\BaseWage;

class CalculateAverage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:average';

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
        $year = $this->ask('Enter the year');
        $semester = $this->ask('Enter the semester');

        if ($year > 0 and $semester != null) {
            $this->info("Working...\n");
            // $Progress = $this->output->createProgressBar();
            // $Progress->setFormat("%current%/%max% [%bar%] %percent:3s%%");
            // $Progress->advance();
            $eco_com_rents = EcoComRent::whereYear("year", '=', $year)->where('semester', $semester)->get();
            $eco_com_procedure = EcoComProcedure::whereYear("year", '=', $year)->where('semester', $semester)->first();
            if (!$eco_com_procedure) {
                $this->error("\n\n Eco Com procedure not found  $year/$semester.\n");
                exit();
            }
            if (BaseWage::whereYear('month_year', $year)->get()->count() == 0 ) {
                $this->error("\n\n BAse wage not found for year  $year.\n");
                exit();
            }
            foreach ($eco_com_rents as $value) {
                $value->delete();
            }
            $average_list = EconomicComplement::select(DB::raw("affiliates.degree_id as degree_id, procedure_modalities.id as procedure_modality_id, min(economic_complements.total_rent) as rmin, max(economic_complements.total_rent) as rmax,round((max(economic_complements.total_rent)+ min(economic_complements.total_rent))/2,2) as average"))
                ->leftJoin('eco_com_modalities', 'economic_complements.eco_com_modality_id', '=', 'eco_com_modalities.id')
                ->leftJoin('procedure_modalities', 'eco_com_modalities.procedure_modality_id', '=', 'procedure_modalities.id')
                ->leftJoin('affiliates', 'economic_complements.affiliate_id', '=', 'affiliates.id')
                ->leftJoin('eco_com_procedures', 'economic_complements.eco_com_procedure_id', '=', 'eco_com_procedures.id')
                ->leftJoin('degrees', 'affiliates.degree_id', '=', 'degrees.id')
                ->leftJoin('base_wages', 'economic_complements.degree_id', '=', 'base_wages.degree_id')
                ->where('economic_complements.eco_com_procedure_id', '=', $eco_com_procedure->id)
                ->whereYear('base_wages.month_year', '=', $year)
                ->whereRaw("economic_complements.total_rent::numeric >= eco_com_procedures.indicator::numeric")
                //->whereRaw('economic_complements.total_rent::numeric <= base_wages.amount::numeric') //MAL
                ->whereIN('economic_complements.eco_com_modality_id', [1, 2])
                ->where(function ($query) {
                    $query->whereNull('economic_complements.aps_disability')
                        ->orWhere('economic_complements.aps_disability', '=', '0');
                })
                ->groupBy('affiliates.degree_id', 'procedure_modalities.id')
                ->orderBy('affiliates.degree_id', 'ASC')->get();

            if ($average_list) {
                foreach ($average_list as $item) {
                    $rent = new EcoComRent();
                    $rent->user_id = 1;
                    $rent->degree_id = $item->degree_id;
                    $rent->procedure_modality_id = $item->procedure_modality_id;
                    $newdate = Carbon::createFromDate($year, 1, 1)->toDateString();
                    $rent->year = $newdate;
                    $rent->semester = $semester;
                    $rent->minor = $item->rmin;
                    $rent->higher = $item->rmax;
                    $rent->average = $item->average;
                    $rent->save();
                }
            }

            // $time_end = microtime(true);
            // $execution_time = ($time_end - $time_start) / 60;
            // $Progress->finish();

            // $this->info("\n\nReport Calculate average:\nExecution time $execution_time [minutes].\n");
        }
    }
}
