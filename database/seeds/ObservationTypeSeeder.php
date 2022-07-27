<?php

use Illuminate\Database\Seeder;
use Muserpol\Operation;

class ObservationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['module_id' => 4, 'name' => 'ObservationType' ],
        ];
        foreach ($statuses as $status) {
            Operation::create($status);
        }
        DB::table('observation_types')->insert([
             ['module_id'=>3,'name' => 'Observación en el registro del trámite.','description'=>'Subsanable','shortened'=>'Observación en el registro del trámite.','type'=>'T'],
             ['module_id'=>3,'name' => 'Documentación observada o insuficiente.','description'=>'Subsanable','shortened'=>'Documentación observada o insuficiente.','type'=>'T'],
             ['module_id'=>3,'name' => 'Periodos observados.','description'=>'Subsanable','shortened'=>'Periodos observados.','type'=>'T'],
             ['module_id'=>3,'name' => 'Error en los datos del afiliado o beneficiarios.','description'=>'Subsanable','shortened'=>'Error en los datos del afiliado o beneficiarios.','type'=>'T'],
             ['module_id'=>3,'name' => 'Inconsistencia en comprobante de depósito bancario.','description'=>'Subsanable','shortened'=>'Inconsistencia en comprobante de depósito bancario.','type'=>'T'],

             ['module_id'=>4,'name' => 'Observación en el registro del trámite.','description'=>'Subsanable','shortened'=>'Observación en el registro del trámite.','type'=>'T'],
             ['module_id'=>4,'name' => 'Documentación observada o insuficiente.','description'=>'Subsanable','shortened'=>'Documentación observada o insuficiente.','type'=>'T'],
             ['module_id'=>4,'name' => 'Periodos observados.','description'=>'Subsanable','shortened'=>'Periodos observados.','type'=>'T'],
             ['module_id'=>4,'name' => 'Error en los datos del afiliado o beneficiarios.','description'=>'Subsanable','shortened'=>'Error en los datos del afiliado o beneficiarios.','type'=>'T'],
             ['module_id'=>4,'name' => 'Inconsistencia en comprobante de depósito bancario.','description'=>'Subsanable','shortened'=>'Inconsistencia en comprobante de depósito bancario.','type'=>'T'],
        ]);
    }
}
