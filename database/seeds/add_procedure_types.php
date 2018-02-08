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
        DB::table('prodedure_types')->insert
        ([['module_id' => '6', 'name' => 'Anticipo Fondo de Retiro Policial'],
        ['module_id' => '3', 'name' => 'Aporte Voluntario Item "0" '],
        ['module_id' => '3', 'name' => 'Expediente Transitorio '],
        ]);      
    }
}
