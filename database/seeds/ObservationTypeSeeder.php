<?php

use Illuminate\Database\Seeder;

class ObservationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('observation_types')->insert([
            // ['module_id'=>3,'name' => 'Inconsistencia de Documentos ','description'=>'Referente a la inconsistencia del documento presentado']
        ]);
    }
}
