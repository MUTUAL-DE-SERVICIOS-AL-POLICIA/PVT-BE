<?php

use Illuminate\Database\Seeder;

class WorkFlowAndStates extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('wf_states')->insert([
        	['id'=>19, 'module_id'=>3,'role_id'=>10,'name'=> 'Área de Recepción Fondo de Retiro','first_shortened'=>'Recepción'],
        	['id'=>20, 'module_id'=>3,'role_id'=>15,'name'=> 'Área de Archivo Fondo de Retiro','first_shortened'=>'Archivo'],
        	['id'=>21, 'module_id'=>3,'role_id'=>14,'name'=> 'Área de Legal Fondo de Retiro','first_shortened'=>'Legal'],
        ]);

        // DB::table('wf_sequences')->insert([
        // 	['workflow_id'=>4,'wf_state_current_id' =>19 ,'wf_state_next_id'=>20,'action'=>'Aprobar' ,'created_at'=>'2018/02/02','updated_at'=>'2018/02/02' ],
        // 	['workflow_id'=>4,'wf_state_current_id' =>20 ,'wf_state_next_id'=>21, 'action'=> 'Aprobar' ,'created_at'=>'2018/02/02','updated_at'=>'2018/02/02' ],
        // 	['workflow_id'=>4,'wf_state_current_id' =>21 ,'wf_state_next_id'=>19, 'action'=> 'Denegar' ,'created_at'=>'2018/02/02','updated_at'=>'2018/02/02' ],
        // 	// ['workflow_id'=>4,'wf_state_current_id' =>21 ,'wf_state_next_id'=>22'Aprobar' ,'created_at'=>'2018/02/02','updated_at'=>'2018/02/02' ],
        // ]);
    }
}
