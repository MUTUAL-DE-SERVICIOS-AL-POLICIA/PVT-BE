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
        DB::table('procedure_modalities')->insert([
        ['id' => '13','procedure_type_id' => '3', 'name' => 'Anticipo Fondo de Retiro Policial', 'is_valid'=> false],
        ['id' => '14','procedure_type_id' => '3', 'name' => 'Aporte Voluntario Item "0" ', 'is_valid' => true],
        ['id' => '15','procedure_type_id' => '3', 'name' => 'Expediente Transitorio ', 'is_valid' =>true],
        ]);      
    }
}
