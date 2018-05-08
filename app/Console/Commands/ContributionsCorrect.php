<?php

namespace Muserpol\Console\Commands;

use Illuminate\Console\Command;
use Muserpol\Models\Contribution\ContributionType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ContributionsCorrect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ContributionsCorrect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige los 10 tipos de contribucion en la plataforma virtual de tramites';

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
        //
        $this->info('hola ');
        $columna =$this->ask('(s/n)quieres eliminar la columna de la tabla contribution_types la columna: group_type_contribution_id ?');
        if($columna=='s'){
            $this->info('Eliminando...');
            Schema::table('contribution_types', function ($table) {
                $table->dropColumn('group_type_contribution_id');
            });
        }else{
            $this->info('No Eliminado');
        }
        $tabla = $this->ask('quieres eliminar la columna de la tabla contribution_types?');
        // if($tabla){
        //     Schema::drop('group_type_contributions');
        // }else{
        //     $this->info('No Eliminado');
        // }

        // ContributionType::destroy($id);

    }
}
