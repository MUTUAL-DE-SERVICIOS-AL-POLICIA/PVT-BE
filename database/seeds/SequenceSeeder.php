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
            ['module_id' => '3', 'name' => 'Área de Jefatura', 'action' => 'Aprobado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['module_id' => '3', 'name' => 'Área de Resolución', 'action' => 'Aprobado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['module_id' => '3', 'name' => 'Regional Santa Cruz', 'action' => 'Recepcionado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['module_id' => '3', 'name' => 'Regional Cochabamba', 'action' => 'Recepcionado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['module_id' => '3', 'name' => 'Regional Oruro', 'action' => 'Recepcionado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['module_id' => '3', 'name' => 'Regional Potosí', 'action' => 'Recepcionado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['module_id' => '3', 'name' => 'Regional Sucre', 'action' => 'Recepcionado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['module_id' => '3', 'name' => 'Regional Taríja', 'action' => 'Recepcionado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
            ['module_id' => '3', 'name' => 'Área de Tesorería ', 'action' => 'Pagado', 'created_at' => '1958/07/21', 'updated_at' => '1958/07/21'],
        ]);
        
        DB::table('wf_states')->insert([
            ['module_id' => '3', 'role_id' => '10', 'name' => 'Área de Recepción Fondo de Retiro', 'first_shortened' => 'Recepción'],
            ['module_id' => '3', 'role_id' => '15', 'name' => 'Área de Archivo Fondo de Retiro', 'first_shortened' => 'Archivo'],
            ['module_id' => '3', 'role_id' => '14', 'name' => 'Área de Legal Fondo de Retiro', 'first_shortened' => 'Legal'],
            ['module_id' => '3', 'role_id' => '12', 'name' => 'Área de Cuentas Individuales Fondo de Retiro', 'first_shortened' => 'Cuentas Individuales'],
            ['module_id' => '3', 'role_id' => '13', 'name' => 'Área de Calificación Fondo de Retiro', 'first_shortened' => 'Calificación'],
            ['module_id' => '3', 'role_id' => '28', 'name' => 'Área de Jefatura Fondo de Retiro', 'first_shortened' => 'Jefatura'],
            ['module_id' => '3', 'role_id' => '14', 'name' => 'Área de Dictamen Legal Fondo de Retiro', 'first_shortened' => 'Dictamen Legal'],
            ['module_id' => '3', 'role_id' => '29', 'name' => 'Área de Resolución Fondo de Retiro', 'first_shortened' => 'Resolución'],
            ['module_id' => '3', 'role_id' => '30', 'name' => 'Regional Santa Cruz', 'first_shortened' => 'Santa Cruz'],
            ['module_id' => '3', 'role_id' => '31', 'name' => 'Regional Cochabamba', 'first_shortened' => 'Cochabamba'],
            ['module_id' => '3', 'role_id' => '32', 'name' => 'Regional Oruro', 'first_shortened' => 'Oruro'],
            ['module_id' => '3', 'role_id' => '33', 'name' => 'Regional Potosí', 'first_shortened' => 'Potosí'],
            ['module_id' => '3', 'role_id' => '34', 'name' => 'Regional Sucre', 'first_shortened' => 'Sucre'],
            ['module_id' => '3', 'role_id' => '35', 'name' => 'Regional Taríja', 'first_shortened' => 'Taríja'],
        ]);

        DB::table('wf_sequences')->insert([
            ['workflow_id' => '4', 'wf_state_current_id' => '27', 'wf_state_next_id' => '19', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '27', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '28', 'wf_state_next_id' => '19', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '28', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '29', 'wf_state_next_id' => '19', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '29', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '30', 'wf_state_next_id' => '19', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '30', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '31', 'wf_state_next_id' => '19', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '31', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '32', 'wf_state_next_id' => '19', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '32', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '19', 'wf_state_next_id' => '20', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '20', 'wf_state_next_id' => '19', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '20', 'wf_state_next_id' => '21', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '21', 'wf_state_next_id' => '20', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '21', 'wf_state_next_id' => '19', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '21', 'wf_state_next_id' => '22', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '22', 'wf_state_next_id' => '23', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '22', 'wf_state_next_id' => '21', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '22', 'wf_state_next_id' => '20', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '22', 'wf_state_next_id' => '19', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '24', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '22', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '21', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '20', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '23', 'wf_state_next_id' => '19', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '25', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '23', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '22', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '21', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '20', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '24', 'wf_state_next_id' => '19', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '25', 'wf_state_next_id' => '26', 'action' => 'Aprobar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '25', 'wf_state_next_id' => '24', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '26', 'wf_state_next_id' => '25', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
            ['workflow_id' => '4', 'wf_state_current_id' => '26', 'wf_state_next_id' => '24', 'action' => 'Denegar','created_at' => '1100/07/21', 'updated_at' =>'1100/07/21'],
        ]);
    }
}
