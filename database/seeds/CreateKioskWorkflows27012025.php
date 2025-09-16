<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Muserpol\Models\Workflow\WorkflowSequence;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\Role;

class CreateKioskWorkflows27012025 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::transaction(function () {
            $role = Role::where('display_name', 'Kiosco Digital')->first();
            if (!$role) {
                $role = Role::create(['module_id' => 2, 'display_name' => 'Kiosco Digital', 'name' => 'CE-kiosco-digital', 'action' => 'Recepcionado']);
                $this->command->info("Role Kiosco Digital creado.");
            } else {
                $this->command->info("Role Kiosco Digital ya existe.");
            }

            $wf_state = WorkflowState::where('name', 'Kiosco Digital')->first();
            if (!$wf_state) {
                $wf_state = WorkflowState::create(['module_id' => 2, 'role_id' => $role->id, 'name' => 'Kiosco Digital', 'first_shortened' => 'Kiosco Digital', 'sequence_number' => 0]);
                $this->command->info("Wf_state Kiosco Digital creado.");
            } else {
                $this->command->info("Wf_state Kiosco Digital ya existe.");
            }

            $wf_state_sequence = WorkflowSequence::where('workflow_id', 1)->where('wf_state_current_id', $wf_state->id)->where('wf_state_next_id', 3)->first();
            if (!$wf_state_sequence) {
                $wf_state_sequence = WorkflowSequence::create(['workflow_id' => 1, 'wf_state_current_id' => $wf_state->id, 'wf_state_next_id' => 3, 'action' => 'Aprobar']);
                $this->command->info("WorkflowSequence Kiosco Digital creado.");
            } else {
                $this->command->info("WorkflowSequence Kiosco Digital ya existe.");
            }
        });
    }
}
