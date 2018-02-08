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
        ([['id' => '1', 'name' => 'Conyugue'],
        ['id' => '2', 'name' => 'Hijo(a)'],
        ['id' => '3', 'name' => 'Padre'],
        ['id' => '4', 'name' => 'Madre'],
        ['id' => '5', 'name' => 'Hermano(a)'],
        //['id' => '6', 'name' => 'Otro'],
        ]);       
    }
}
