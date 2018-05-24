<?php

use Illuminate\Database\Seeder;

class ReceptionTablesUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ret_fun_states')->insert([
            ['name' =>  'En proceso'],
            ['name' =>  'Pendiente'],
            ['name' =>  'Eliminado']
        ]);
    }
}
