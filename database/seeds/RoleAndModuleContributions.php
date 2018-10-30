<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Module;
use Muserpol\Models\Role;

class RoleAndModuleContributions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['name' => 'Contribuciones', 'description' => 'Contribuciones'],
        ];
        foreach ($statuses as $status) {
            Module::create($status);
        }
        $statuses = [
            ['module_id' => 11, 'name' => 'Aportes', 'action'=>'realizado'],
        ];
        foreach ($statuses as $status) {
            Role::create($status);
        }
    }
}
