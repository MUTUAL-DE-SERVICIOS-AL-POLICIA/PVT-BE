<?php

namespace Muserpol\Console\Commands;

use Illuminate\Console\Command;
use Muserpol\Operation;
use Muserpol\Action;
use Muserpol\Permission;

class PermissionQuotaAid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:permission_quota_aid';

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
        $this->info('working...');
        $operations = Operation::where('module_id',4)->get();
        $actions = Action::all();
        foreach ($operations as $o) {
            foreach ($actions as $a) {
                $p = new Permission();
                $p->operation_id = $o->id;
                $p->action_id = $a->id;
                $p->save();
            }
        }
        $this->info('done');
    }
}
