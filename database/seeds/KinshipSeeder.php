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
            ['name' => 'Titular'],
            ['name' => 'Conyugue'],
            ['name' => 'Hijo(a)'],
            ['name' => 'Padre'],
            ['name' => 'Madre'],
            ['name' => 'Hermano(a)'],
            ['name' => 'Otro']
        ]);       
    }
}
