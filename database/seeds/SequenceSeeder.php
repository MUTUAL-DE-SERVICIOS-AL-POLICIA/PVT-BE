<?php

use Illuminate\Database\Seeder;

class SequenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['id' => '28', 'module_id' => '3', 'name' => 'Jefatura', 'action' => 'Aprobado'],
            ['id' => '29', 'module_id' => '3', 'name' => 'Resolución', 'action' => 'Aprobado'],
            ['id' => '30', 'module_id' => '3', 'name' => 'Regional Santa Cruz', 'action' => 'Recepcionado'],
            ['id' => '31', 'module_id' => '3', 'name' => 'Regional Cochabamba', 'action' => 'Recepcionado'],
            ['id' => '32', 'module_id' => '3', 'name' => 'Regional Oruro', 'action' => 'Recepcionado'],
            ['id' => '33', 'module_id' => '3', 'name' => 'Regional Potosí', 'action' => 'Recepcionado'],
            ['id' => '34', 'module_id' => '3', 'name' => 'Regional Sucre', 'action' => 'Recepcionado'], 
            ['id' => '35', 'module_id' => '3', 'name' => 'Regional Taríja', 'action' => 'Recepcionado'],       
        ]);
        DB::table('wf_states')->insert([
            ['id' => '22', 'module_id' => '3', 'role_id' => '12', 'name' => 'Area de Cuentas Individuales Fondo de Retiro', 'fist_shortened' => 'Cuentas Individuales'],
            ['id' => '23', 'module_id' => '3', 'role_id' => '13', 'name' => 'Area de Calificación Fondo de Retiro', 'fist_shortened' => 'Calificación'],
            ['id' => '24', 'module_id' => '3', 'role_id' => '28', 'name' => 'Area de Jefatura Fondo de Retiro', 'fist_shortened' => 'Jefatura'],
            ['id' => '25', 'module_id' => '3', 'role_id' => '14', 'name' => 'Area de Dictamen Legal Fondo de Retiro', 'fist_shortened' => 'Dictamen Legal'],
            ['id' => '26', 'module_id' => '3', 'role_id' => '29', 'name' => 'Area de Resolución Fondo de Retiro', 'fist_shortened' => 'Resolución'],
            ['id' => '27', 'module_id' => '3', 'role_id' => '30', 'name' => 'Regional Santa Cruz', 'fist_shortened' => 'Santa Cruz'],
            ['id' => '28', 'module_id' => '3', 'role_id' => '31', 'name' => 'Regional Cochabamba', 'fist_shortened' => 'Cochabamba'],
            ['id' => '29', 'module_id' => '3', 'role_id' => '32', 'name' => 'Regional Oruro', 'fist_shortened' => 'Oruro'],
            ['id' => '30', 'module_id' => '3', 'role_id' => '33', 'name' => 'Regional Potosí', 'fist_shortened' => 'Potosí'],
            ['id' => '31', 'module_id' => '3', 'role_id' => '34', 'name' => 'Regional Sucre', 'fist_shortened' => 'Sucre'],
            ['id' => '32', 'module_id' => '3', 'role_id' => '35', 'name' => 'Regional Taríja', 'fist_shortened' => 'Taríja'],
        ]);

        DB::table('wf_sequences')->insert([
            ['workflow_id' => '4', 'wf_state_current_id' => '27', 'wf_state_next_id' => '19', 'action' => 'Aprobar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '27', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '28', 'wf_state_next_id' => '19', 'action' => 'Aprobar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '28', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '29', 'wf_state_next_id' => '19', 'action' => 'Aprobar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '29', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '30', 'wf_state_next_id' => '19', 'action' => 'Aprobar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '30', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '31', 'wf_state_next_id' => '19', 'action' => 'Aprobar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '31', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '32', 'wf_state_next_id' => '19', 'action' => 'Aprobar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '32', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '20', 'action' => 'Aprobar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '20', 'wf_state_next_id' => '19', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '20', 'wf_state_next_id' => '21', 'action' => 'Aprobar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '21', 'wf_state_next_id' => '20', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '21', 'wf_state_next_id' => '19', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '21', 'wf_state_next_id' => '22', 'action' => 'Aprobar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '22', 'wf_state_next_id' => '23', 'action' => 'Aprobar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '22', 'wf_state_next_id' => '21', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '22', 'wf_state_next_id' => '20', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '22', 'wf_state_next_id' => '19', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '24', 'action' => 'Aprobar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '22', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '21', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '20', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '19', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '25', 'action' => 'Aprobar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '23', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '22', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '21', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '20', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '19', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '25', 'wf_state_next_id' => '26', 'action' => 'Aprobar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '25', 'wf_state_next_id' => '24', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '26', 'wf_state_next_id' => '25', 'action' => 'Denegar'],
            ['workflow_id' => '4', 'wf_state_current_id' => '26', 'wf_state_next_id' => '24', 'action' => 'Denegar'],
        ]);
    }
}
