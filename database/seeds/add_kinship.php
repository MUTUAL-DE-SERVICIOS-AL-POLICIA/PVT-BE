<?php

use Illuminate\Database\Seeder;

class add_kinship extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kinships')->insert
        ([['id' => '1', 'name' => 'Titular'],
        ['id' => '2', 'name' => 'Esposa/Esposo'],
        ['id' => '3', 'name' => 'Padre/Madre'],
        ['id' => '4', 'name' => 'Hijo/Hija'],
        ['id' => '5', 'name' => 'Apoderado'],
        ['id' => '6', 'name' => 'Tutor/Tutora'],
        ['id' => '7', ]
        ]);

        DB::table('procedure_types')->insert([
            ['module_id' => '3', 'name' => 'Pago Global de Aportes'],
            ['module_id' => '3', 'name' => 'Pago de Fondo de Retiro'],
            ['module_id' => '4', 'name' => 'Pago Cuota Mortuoria'],
            ['module_id' => '5', 'name' => 'Pago Auxilio Mortuorio'],            
        ]);
    }
}
