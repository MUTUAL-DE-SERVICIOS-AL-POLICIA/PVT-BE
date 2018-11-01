<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Module;
use Muserpol\Models\Role;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\Workflow\Workflow;
use Muserpol\Models\Workflow\WorkflowSequence;

class RoleAndModuleContributions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $statuses = [
        //     ['name' => 'Contribuciones', 'description' => 'Contribuciones'],
        // ];
        // foreach ($statuses as $status) {
        //     Module::create($status);
        // }
        $statuses = [
            // ['module_id' => 11, 'name' => 'Aportes', 'action'=>'realizado'],
            ['module_id' => 11, 'name' => 'Tesorería Aportes', 'action'=>'realizado'],
            ['module_id' => 11, 'name' => 'Archivo Aportes', 'action'=>'realizado'],
        ];
        foreach ($statuses as $status) {
            Role::create($status);
        }
        $statuses = [
            ['module_id' => 11, 'name' => 'Aportes'],
        ];
        foreach ($statuses as $status) {
            Workflow::create($status);
        }
        $statuses = [
            ['module_id' => 11, 'role_id' => 56, 'name' => 'Cuentas Individuales Aportes', 'first_shortened' => 'Cuentas Individuales', 'sequence_number' => 1],
            ['module_id' => 11, 'role_id' => 57, 'name' => 'Tesorería Aportes', 'first_shortened' => 'Tesorería', 'sequence_number' => 2],
            ['module_id' => 11, 'role_id' => 58, 'name' => 'Archivo Aportes', 'first_shortened' => 'Archivo', 'sequence_number' => 3],
        ];
        foreach ($statuses as $status) {
            WorkflowState::create($status);
        }
        $statuses = [
            ['workflow_id' => 7, 'wf_state_current_id' => 49, 'wf_state_next_id' => 50, 'action'=>'Aprobar'],
            ['workflow_id' => 7, 'wf_state_current_id' => 50, 'wf_state_next_id' => 51, 'action'=>'Aprobar'],
            ['workflow_id' => 7, 'wf_state_current_id' => 51, 'wf_state_next_id' => 52, 'action'=>'Aprobar'],
        ];
        foreach ($statuses as $status) {
            WorkflowSequence::create($status);
        }
    }
}
