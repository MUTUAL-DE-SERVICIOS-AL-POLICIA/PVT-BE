<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Role;

class RolesQuotaAidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['module_id' => 4, 'name' => 'Área de Calificación', 'action' => 'Calificado'],
            ['module_id' => 4, 'name' => 'Área de Revisión Legal', 'action' => 'Revisado'],
            ['module_id' => 4, 'name' => 'Área de Cuentas Individuales', 'action' => 'Revisado'],
            ['module_id' => 4, 'name' => 'Área de Recepción', 'action' => 'Recepcionado'],
            ['module_id' => 4, 'name' => 'Área de Dictamen Legal Dictamen', 'action' => 'Realizado'],
            ['module_id' => 4, 'name' => 'Área de Archivo', 'action' => 'Revisado'],
            ['module_id' => 4, 'name' => 'Área de Jefatura', 'action' => 'Aprobado'],
            ['module_id' => 4, 'name' => 'Área de Resolución', 'action' => 'Aprobado'],
            ['module_id' => 4, 'name' => 'Regional Santa Cruz', 'action' => 'Recepcionado'],
            ['module_id' => 4, 'name' => 'Regional Cochabamba', 'action' => 'Recepcionado'],
            ['module_id' => 4, 'name' => 'Regional Oruro', 'action' => 'Recepcionado'],
            ['module_id' => 4, 'name' => 'Regional Potosí', 'action' => 'Recepcionado'],
            ['module_id' => 4, 'name' => 'Regional Sucre', 'action' => 'Recepcionado'],
            ['module_id' => 4, 'name' => 'Regional Tarija', 'action' => 'Recepcionado'],
            ['module_id' => 4, 'name' => 'Área de Tesorería', 'action' => 'Pagado'],
        ];
        foreach ($statuses as $status) {
            Role::create($status);
        }

    }
}
