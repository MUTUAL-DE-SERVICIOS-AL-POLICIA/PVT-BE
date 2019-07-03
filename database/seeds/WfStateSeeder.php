<?php

use Illuminate\Database\Seeder;
use Illuminste\Support\Facades\DB;

class WfStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('wf_states')->insert([
         
            'module_id'=>'2',
            'role_id'=>'17',
            'name'=>'Area de Juridica',
            'first_shortened'=>'Juridica',
            'sequence_number'=>'0',
    

        ]);
    }
}
