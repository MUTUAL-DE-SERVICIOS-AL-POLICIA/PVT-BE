<?php

namespace Muserpol\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Models\Contribution\ContributionType;
use Muserpol\Models\Contribution\Contribution;
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
        $this->info('hola ');
        $registros=ContributionType::all();
        $nuevosnombres=array(
         'Período reconocido por comando',
        'Período en item 0 Con Aporte',
        'Período en item 0 Sin Aporte',
        'Período de Batallón de Seguridad Física Con Aporte',
        'Período de Batallón de Seguridad Física Sin Aporte',
       'Periodos anteriores a Mayo de 1976 Sin Aporte',
       'Período Certificación Con Aporte',
        'Período Certificación Sin Aporte',
        'Período no Trabajado',
        'Disponibilidad'
        );
        print_r($nuevosnombres);
        //DB::table('contribution_types')->truncate();

        foreach($nuevosnombres as $nuevonombre){
            print_r($nuevonombre);
            $this->info('compara con');
            $sw=0;//iguales
            foreach($registros as $registro){
                $this->info($registro->name);
                $this->info('esto');
                if(strcasecmp(($registro->name), $nuevonombre) == 0){ //iguales
                    
                }else{//no iguales
                    $sw=1; //hay diferentes
                    $iguales=Contribution::whereNotNull('contribution_type_id')->where('contribution_type_id',$registro->id)->get();
                    foreach($iguales as $igual){
                        $nuevo=Contribution::find($iguales->id);
                        $nuevo->contribution_type_id=null;
                        $this->info('contribution');
                    }
                    $this->info($iguales);
                    $this->info('--------');
                    ContributionType::destroy($registro->id);
                }
            }
            if($sw==0){

            }else{
                DB::table('contribution_types')->insert([ ['name' => $nuevonombre, 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],  ]);
            }
        }
    

    }

}
