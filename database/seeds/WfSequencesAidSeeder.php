<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Workflow\WorkflowSequence;

class WfSequencesAidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['workflow_id' => 6, 'wf_state_current_id' => 33, 'wf_state_next_id' => 34, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 34, 'wf_state_next_id' => 35, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 35, 'wf_state_next_id' => 36, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 36, 'wf_state_next_id' => 37, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 37, 'wf_state_next_id' => 38, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 38, 'wf_state_next_id' => 40, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 39, 'wf_state_next_id' => 38, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 40, 'wf_state_next_id' => 57, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 41, 'wf_state_next_id' => 33, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 42, 'wf_state_next_id' => 33, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 43, 'wf_state_next_id' => 33, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 44, 'wf_state_next_id' => 33, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 45, 'wf_state_next_id' => 33, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 46, 'wf_state_next_id' => 33, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 52, 'wf_state_next_id' => 33, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 53, 'wf_state_next_id' => 33, 'action' => 'Aprobar', ],
            ['workflow_id' => 6, 'wf_state_current_id' => 57, 'wf_state_next_id' => 58, 'action' => 'Aprobar', ],
        ];
        foreach ($statuses as $status) {
            WorkflowSequence::firstOrCreate($status);
        }
 }
}
