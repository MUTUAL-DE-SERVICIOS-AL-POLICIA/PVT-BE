<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\Workflow\WorkflowSequence;

class WfStateAndWfSequencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['module_id' => 4, 'role_id' => 40, 'name' => 'Área de Recepción Cuota y Auxilio Mortuorio', 'first_shortened' => 'Recepción', 'sequence_number' => 1],
            ['module_id' => 4, 'role_id' => 42, 'name' => 'Área de Archivo Cuota y Auxilio Mortuorio', 'first_shortened' => 'Archivo', 'sequence_number' => 2],
            ['module_id' => 4, 'role_id' => 38, 'name' => 'Área de Legal Cuota y Auxilio Mortuorio', 'first_shortened' => 'Legal', 'sequence_number' => 3],
            ['module_id' => 4, 'role_id' => 39, 'name' => 'Área de Cuentas Individuales Cuota y Auxilio Mortuorio', 'first_shortened' => 'Cuentas Individuales', 'sequence_number' => 4],
            ['module_id' => 4, 'role_id' => 37, 'name' => 'Área de Calificación Cuota y Auxilio Mortuorio', 'first_shortened' => 'Calificación', 'sequence_number' => 5],
            ['module_id' => 4, 'role_id' => 43, 'name' => 'Área de Jefatura Cuota y Auxilio Mortuorio', 'first_shortened' => 'Jefatura', 'sequence_number' => 6],
            ['module_id' => 4, 'role_id' => 41, 'name' => 'Área de Dictamen Legal Cuota y Auxilio Mortuorio', 'first_shortened' => 'Dictamen Legal', 'sequence_number' => 7],
            ['module_id' => 4, 'role_id' => 44, 'name' => 'Área de Resolución Cuota y Auxilio Mortuorio', 'first_shortened' => 'Resolución', 'sequence_number' => 8],
            ['module_id' => 4, 'role_id' => 45, 'name' => 'Regional Santa Cruz', 'first_shortened' => 'Santa Cruz', 'sequence_number' => 0],
            ['module_id' => 4, 'role_id' => 46, 'name' => 'Regional Cochabamba', 'first_shortened' => 'Cochabamba', 'sequence_number' => 0],
            ['module_id' => 4, 'role_id' => 47, 'name' => 'Regional Oruro', 'first_shortened' => 'Oruro', 'sequence_number' => 0],
            ['module_id' => 4, 'role_id' => 48, 'name' => 'Regional Potosí', 'first_shortened' => 'Potosí', 'sequence_number' => 0],
            ['module_id' => 4, 'role_id' => 49, 'name' => 'Regional Sucre', 'first_shortened' => 'Sucre', 'sequence_number' => 0],
            ['module_id' => 4, 'role_id' => 50, 'name' => 'Regional Tarija', 'first_shortened' => 'Tarija', 'sequence_number' => 0],
        ];
        foreach ($statuses as $status) {
            WorkflowState::create($status);
        }

        $statuses = [
            ['workflow_id' => 4, 'wf_state_current_id' => 33, 'wf_state_next_id' => 34, 'action' => 'Aprobar',],
            ['workflow_id' => 4, 'wf_state_current_id' => 34, 'wf_state_next_id' => 35, 'action' => 'Aprobar',],
            ['workflow_id' => 4, 'wf_state_current_id' => 35, 'wf_state_next_id' => 36, 'action' => 'Aprobar',],
            ['workflow_id' => 4, 'wf_state_current_id' => 36, 'wf_state_next_id' => 37, 'action' => 'Aprobar',],
            ['workflow_id' => 4, 'wf_state_current_id' => 37, 'wf_state_next_id' => 38, 'action' => 'Aprobar',],
            ['workflow_id' => 4, 'wf_state_current_id' => 38, 'wf_state_next_id' => 39, 'action' => 'Aprobar',],
            ['workflow_id' => 4, 'wf_state_current_id' => 39, 'wf_state_next_id' => 40, 'action' => 'Aprobar',],
            ['workflow_id' => 4, 'wf_state_current_id' => 41, 'wf_state_next_id' => 33, 'action' => 'Aprobar',],
            ['workflow_id' => 4, 'wf_state_current_id' => 42, 'wf_state_next_id' => 33, 'action' => 'Aprobar',],
            ['workflow_id' => 4, 'wf_state_current_id' => 43, 'wf_state_next_id' => 33, 'action' => 'Aprobar',],
            ['workflow_id' => 4, 'wf_state_current_id' => 44, 'wf_state_next_id' => 33, 'action' => 'Aprobar',],
            ['workflow_id' => 4, 'wf_state_current_id' => 45, 'wf_state_next_id' => 33, 'action' => 'Aprobar',],
            ['workflow_id' => 4, 'wf_state_current_id' => 46, 'wf_state_next_id' => 33, 'action' => 'Aprobar',],
        ];
        foreach ($statuses as $status) {
            WorkflowSequence::create($status);
        }
    }
}
