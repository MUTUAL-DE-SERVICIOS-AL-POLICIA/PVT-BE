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
            ['id' => '28', 'module_id' => '3', 'name' => 'Área de Jefatura', 'action' => 'Aprobado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['id' => '29', 'module_id' => '3', 'name' => 'Área de Resolución', 'action' => 'Aprobado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['id' => '30', 'module_id' => '3', 'name' => 'Regional Santa Cruz', 'action' => 'Recepcionado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['id' => '31', 'module_id' => '3', 'name' => 'Regional Cochabamba', 'action' => 'Recepcionado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['id' => '32', 'module_id' => '3', 'name' => 'Regional Oruro', 'action' => 'Recepcionado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['id' => '33', 'module_id' => '3', 'name' => 'Regional Potosí', 'action' => 'Recepcionado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['id' => '34', 'module_id' => '3', 'name' => 'Regional Sucre', 'action' => 'Recepcionado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'], 
            ['id' => '35', 'module_id' => '3', 'name' => 'Regional Taríja', 'action' => 'Recepcionado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],       
        ]);
        DB::table('wf_states')->insert([
            ['id' => '19', 'module_id' => '3', 'role_id' => '10', 'name' => 'Área de Recepción Fondo de Retiro', 'first_shortened' => 'Recepción'],
        	['id' => '20', 'module_id' => '3', 'role_id' => '15', 'name' => 'Área de Archivo Fondo de Retiro', 'first_shortened' => 'Archivo'],
        	['id' => '21', 'module_id' => '3', 'role_id' => '14', 'name' => 'Área de Legal Fondo de Retiro', 'first_shortened' => 'Legal'],
            ['id' => '22', 'module_id' => '3', 'role_id' => '12', 'name' => 'Área de Cuentas Individuales Fondo de Retiro', 'first_shortened' => 'Cuentas Individuales'],
            ['id' => '23', 'module_id' => '3', 'role_id' => '13', 'name' => 'Área de Calificación Fondo de Retiro', 'first_shortened' => 'Calificación'],
            ['id' => '24', 'module_id' => '3', 'role_id' => '28', 'name' => 'Área de Jefatura Fondo de Retiro', 'first_shortened' => 'Jefatura'],
            ['id' => '25', 'module_id' => '3', 'role_id' => '14', 'name' => 'Área de Dictamen Legal Fondo de Retiro', 'first_shortened' => 'Dictamen Legal'],
            ['id' => '26', 'module_id' => '3', 'role_id' => '29', 'name' => 'Área de Resolución Fondo de Retiro', 'first_shortened' => 'Resolución'],
            ['id' => '27', 'module_id' => '3', 'role_id' => '30', 'name' => 'Regional Santa Cruz', 'first_shortened' => 'Santa Cruz'],
            ['id' => '28', 'module_id' => '3', 'role_id' => '31', 'name' => 'Regional Cochabamba', 'first_shortened' => 'Cochabamba'],
            ['id' => '29', 'module_id' => '3', 'role_id' => '32', 'name' => 'Regional Oruro', 'first_shortened' => 'Oruro'],
            ['id' => '30', 'module_id' => '3', 'role_id' => '33', 'name' => 'Regional Potosí', 'first_shortened' => 'Potosí'],
            ['id' => '31', 'module_id' => '3', 'role_id' => '34', 'name' => 'Regional Sucre', 'first_shortened' => 'Sucre'],
            ['id' => '32', 'module_id' => '3', 'role_id' => '35', 'name' => 'Regional Taríja', 'first_shortened' => 'Taríja'],
        ]);

        DB::table('wf_sequences')->insert([
            ['id' => '117', 'workflow_id' => '4', 'wf_state_current_id' => '27', 'wf_state_next_id' => '19', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '118', 'workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '27', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '119', 'workflow_id' => '4', 'wf_state_current_id' => '28', 'wf_state_next_id' => '19', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '120', 'workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '28', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '121', 'workflow_id' => '4', 'wf_state_current_id' => '29', 'wf_state_next_id' => '19', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '122', 'workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '29', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '123', 'workflow_id' => '4', 'wf_state_current_id' => '30', 'wf_state_next_id' => '19', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '124', 'workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '30', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '125', 'workflow_id' => '4', 'wf_state_current_id' => '31', 'wf_state_next_id' => '19', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '126', 'workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '31', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '127', 'workflow_id' => '4', 'wf_state_current_id' => '32', 'wf_state_next_id' => '19', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '128', 'workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '32', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '129', 'workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '20', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '130', 'workflow_id' => '4', 'wf_state_current_id' => '20', 'wf_state_next_id' => '19', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '131', 'workflow_id' => '4', 'wf_state_current_id' => '20', 'wf_state_next_id' => '21', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '132', 'workflow_id' => '4', 'wf_state_current_id' => '21', 'wf_state_next_id' => '20', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '133', 'workflow_id' => '4', 'wf_state_current_id' => '21', 'wf_state_next_id' => '19', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '134', 'workflow_id' => '4', 'wf_state_current_id' => '21', 'wf_state_next_id' => '22', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '135', 'workflow_id' => '4', 'wf_state_current_id' => '22', 'wf_state_next_id' => '23', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '136', 'workflow_id' => '4', 'wf_state_current_id' => '22', 'wf_state_next_id' => '21', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '137', 'workflow_id' => '4', 'wf_state_current_id' => '22', 'wf_state_next_id' => '20', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '138', 'workflow_id' => '4', 'wf_state_current_id' => '22', 'wf_state_next_id' => '19', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '139', 'workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '24', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '140', 'workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '22', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '141', 'workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '21', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '142', 'workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '20', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '143', 'workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '19', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '144', 'workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '25', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '145', 'workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '23', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '146', 'workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '22', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '147', 'workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '21', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '148', 'workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '20', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '149', 'workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '19', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '150', 'workflow_id' => '4', 'wf_state_current_id' => '25', 'wf_state_next_id' => '26', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '151', 'workflow_id' => '4', 'wf_state_current_id' => '25', 'wf_state_next_id' => '24', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '152', 'workflow_id' => '4', 'wf_state_current_id' => '26', 'wf_state_next_id' => '25', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['id' => '153', 'workflow_id' => '4', 'wf_state_current_id' => '26', 'wf_state_next_id' => '24', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
        ]);
    }
}
