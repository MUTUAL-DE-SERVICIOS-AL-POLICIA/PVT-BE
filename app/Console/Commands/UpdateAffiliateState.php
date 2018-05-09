<?php

namespace Muserpol\Console\Commands;

use Illuminate\Console\Command;
use Muserpol\Models\RetirementFund\RetirementFund;

class UpdateAffiliateState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:affiliate_state';

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
        global $Progress;
        $password = $this->ask('Enter the password');
        if (true) {
            $time_start = microtime(true);
            $this->info("Working...\n");
            $Progress = $this->output->createProgressBar();
            $Progress->setFormat("%current%/%max% [%bar%] %percent:3s%%");
            $count = 0;
            $ret_funds = RetirementFund::all();
            foreach ($ret_funds as $key => $r) {
                $affiliate = $r->affiliate;
                switch ($r->procedure_modality_id) {
                    case 1:
                    case 4:
                        $affiliate->affiliate_state_id = 4;
                        break;
                    case 2:
                    case 3:
                    case 5:
                    case 6:
                    case 7:
                        $affiliate->affiliate_state_id = 5;
                        break;
                    default:
                        $this->info("error");
                        break;
                }
                $affiliate->save();

            
            }
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start) / 60;
            $Progress->finish();

            $this->info("\n\nupdate $count states:\n
            Execution time $execution_time [minutes].\n");
        } else {
            $this->error('Incorrect password!');
            exit();
        }
    }
}
