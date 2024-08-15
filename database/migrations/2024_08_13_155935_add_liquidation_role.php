<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Muserpol\Models\Workflow\WorkflowState;
use Symfony\Component\Console\Output\ConsoleOutput;

use Muserpol\Models\Role;


class AddLiquidationRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = Role::where('display_name','Área de Liquidación')->first();
        if($role == null){
            DB::statement("
            insert into roles (module_id, display_name, action, created_at, updated_at, name)
            values (4, 'Área de Liquidación', 'Aprobado', NOW(), NOW(), 'CAM-area-de-liquidacion');
            ");
        }

        DB::statement("
        update wf_states 
        set deleted_at = NOW()
        where module_id = 4 and sequence_number = 7 and deleted_at is null;
        ");

        $state = WorkflowState::where('name','Área de Liquidación Cuota y Auxilio Mortuorio')->first();
        if($state == null){
            DB::statement("
            insert into wf_states (module_id, role_id, name, first_shortened, sequence_number)
            values (4, (select id from roles where display_name = 'Área de Liquidación'), 'Área de Liquidación Cuota y Auxilio Mortuorio', 'Liquidación', 7);
            ");
        }

        DB::statement("
        update wf_sequences 
        set wf_state_current_id = (select id from wf_states where name = 'Área de Liquidación Cuota y Auxilio Mortuorio')
        where wf_state_current_id = 40;
        ");
        
        DB::statement("
        update wf_sequences 
        set wf_state_next_id = (select id from wf_states where name = 'Área de Liquidación Cuota y Auxilio Mortuorio')
        where wf_state_next_id = 40;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
