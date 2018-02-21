<?php

use Illuminate\Database\Seeder;

class KinshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kinships')->insert([
            ['id' => '1', 'name' => 'Titular'],
            ['id' => '2', 'name' => 'Conyugue'],
            ['id' => '3', 'name' => 'Hijo(a)'],
            ['id' => '4', 'name' => 'Padre'],
            ['id' => '5', 'name' => 'Madre'],
            ['id' => '6', 'name' => 'Hermano(a)'],
            ['id' => '6', 'name' => 'Otro']
        ]);       
    }
}
