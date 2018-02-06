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
        ]);       
    }
}
