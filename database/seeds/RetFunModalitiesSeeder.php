<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RetFunModalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB:table('ret_fun_modalities')->insert([
            ['ret_fun_types_id' => '1', 'name' => 'Fallecimiento'],
            ['ret_fun_types_id' => '1', 'name' => 'Retiro forzoso(Invalidez Permanente)'],
            ['ret_fun_types_id' => '2', 'name' => 'Jubilación'],
            ['ret_fun_types_id' => '2', 'name' => 'Fallecimiento'],
            ['ret_fun_types_id' => '2', 'name' => 'Reti ro forzoso'],            
            ['ret_fun_types_id' => '2', 'name' => 'Retiro forzoso(Invalidez Permanente)'],
            ['ret_fun_types_id' => '2', 'name' => 'Retiro Voluntario'],
        ]);
    }
}
