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
         'Servicio Activo',
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
        
            $nuevo=Contribution::find(6);
            $nuevo->contribution_type_id=null;
            $this->info($nuevo);
        
        /*foreach($nuevosnombres as $nuevonombre){
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
        }*/


        //$name=
      //  DB::table('contribution_types')->insert([ ['name' => , 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],  ]);
        /*if($sw==1){
            $this->info('se eliminaran');
            DB::table('contribution_types')->truncate();

            $this->info('se registraran nuevos');
            DB::table('contribution_types')->insert([
                ['name' => 'Servicio Activo', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
                ['name' => 'Período en item 0 Con Aporte', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
                ['name' => 'Período en item 0 Sin Aporte', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
                ['name' => 'Período de Batallón de Seguridad Física Con Aporte', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
                ['name' => 'Período de Batallón de Seguridad Física Sin Aporte', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
                ['name' => 'Periodos anteriores a Mayo de 1976 Sin Aporte', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
                ['name' => 'Período Certificación Con Aporte', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
                ['name' => 'Período Certificación Sin Aporte', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
                ['name' => 'Período no Trabajado', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
                ['name' => 'Disponibilidad', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
            ]);
        }else{
            $this->info('Son los mismos registros');
        }*/

        //$this->info($sw);
        //
        /*$this->info('hola ');
        if($this->confirm('quieres eliminar la columna de la tabla contribution_types la columna: group_type_contribution_id ?')){
            $this->info('Eliminando...');
            if(Schema::hasColumn('contribution_types','group_type_contribution_id')){
                $this->info('si hay');
                // DB::table('contribution_types')->delete();
                Schema::table('contribution_types', function ($table) {
                    $table->dropColumn('group_type_contribution_id');
                });
            }else{
                $this->info('No existe la columna');
            }
            // Schema::table('contribution_types', function ($table) {
            //     $table->dropColumn('group_type_contribution_id');
            // });
        }else{
            $this->info('No Eliminado');
        }

        if($this->confirm('quieres eliminar la tabla group_type_contributions?')){
            if(Schema::hasTable('group_type_contributions')){
                $this->info('si hay la tabla');
                Schema::drop('group_type_contributions');
            }else{
                $this->info('No existe la tabla');
            }
        }
        //     Schema::drop('group_type_contributions');
        // }else{
        //     $this->info('No Eliminado');
        // }

        // ContributionType::destroy($id);
*/

    }

}
