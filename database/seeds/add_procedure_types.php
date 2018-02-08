<?php

use Illuminate\Database\Seeder;

class add_procedure_types extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('procedure_types')->insert([
        ['id' => '9', 'module_id' => '6', 'name' => 'Anticipo Fondo de Retiro Policial'],
        ['id' => '10', 'module_id' => '3', 'name' => 'Aporte Voluntario Item "0" '],
        ['id' => '11', 'module_id' => '3', 'name' => 'Expediente Transitorio '],
        ]);      
    }
}
