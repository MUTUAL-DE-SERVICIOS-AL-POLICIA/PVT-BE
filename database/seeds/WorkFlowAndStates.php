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
        
        DB::table('procedure_modalities')->insert([
            ['id' => '13','procedure_type_id' => '3', 'name' => 'Anticipo Fondo de Retiro Policial', 'is_valid'=> false],
            ['id' => '14','procedure_type_id' => '3', 'name' => 'Aporte Voluntario Item "0" ', 'is_valid' => true],
            ['id' => '15','procedure_type_id' => '3', 'name' => 'Expediente Transitorio ', 'is_valid' =>true],
            ]);  
        // DB::table('wf_sequences')->insert([
        // 	['workflow_id'=>4,'wf_state_current_id' =>19 ,'wf_state_next_id'=>20,'action'=>'Aprobar' ,'created_at'=>'2018/02/02','updated_at'=>'2018/02/02' ],
        // 	['workflow_id'=>4,'wf_state_current_id' =>20 ,'wf_state_next_id'=>21, 'action'=> 'Aprobar' ,'created_at'=>'2018/02/02','updated_at'=>'2018/02/02' ],
        // 	['workflow_id'=>4,'wf_state_current_id' =>21 ,'wf_state_next_id'=>19, 'action'=> 'Denegar' ,'created_at'=>'2018/02/02','updated_at'=>'2018/02/02' ],
        // 	// ['workflow_id'=>4,'wf_state_current_id' =>21 ,'wf_state_next_id'=>22'Aprobar' ,'created_at'=>'2018/02/02','updated_at'=>'2018/02/02' ],
        // ]);
    }
}
