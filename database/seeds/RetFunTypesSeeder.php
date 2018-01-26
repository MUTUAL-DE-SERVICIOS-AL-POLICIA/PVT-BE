<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RetFunTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB:table('ret_fun_types')->insert([
           ['name' => 'Pago Global'],
           ['name' => 'Pago de Fondo de Retiro'],                
        ]);           
    }
}
