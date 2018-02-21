<?php

use Illuminate\Database\Seeder;
use Faker\Factory as F;

class add_fake_registers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = F::create('App\Users');
                
        for($i=1;$i<30;$i++)
        {
            DB::table('retirement_funds')->insert([
                'user_id'=>$faker->numberBetween($min = 1, $max = 20),
                'affiliate_id' => $faker -> numberBetween($min = 60, $max = 190),
                'procedure_modality_id' => $faker -> numberBetween($min = 1, $max = 12),
                'ret_fun_procedure_id' => 1,
                'city_start_id' => $faker -> numberBetween($min = 1, $max = 9),
                'city_end_id' => $faker -> numberBetween($min = 1, $max = 9),
                'code' => $faker->numerify('###/2018'),                
                'reception_date' => $faker -> date($format = 'Y-m-d', $max = 'now'),     
                'type' => $faker -> randomElement($array = array ('Pago','Anticipo')),
                'subtotal' =>$faker-> numberBetween($min = 1000, $max = 10000), 
                'total'=> $faker -> numberBetween($min = 10000, $max = 15000), 
                'workflow_id'=> $faker -> numberBetween($min = 1, $max = 7),
                'wf_state_current_id' => $faker -> numberBetween($min = 3, $max = 32),
                
                ]);
        }
        for($i=1;$i<3;$i++)
        {
            DB::table('quota_aid_procedures')->insert([
                'hierarchy_id'=>$faker->numberBetween($min = 1, $max = 5),
                'type_mortuary' => $faker -> randomElement($array = array ('Titular','Conyuge', 'Viuda')),
                'procedure_modality_id' => $faker->numberBetween($min = 1, $max = 12),                
                'amount' => $faker -> numberBetween($min = 1, $max = 12),
                'year' => $faker -> date($format = 'Y-m-d', $max = 'now'),
                 ]);
        }    

        for($i=1;$i<150;$i++)
        {
            DB::table('quota_aid_mortuaries')->insert([
                'user_id'=>$faker->numberBetween($min = 1, $max = 20),
                'affiliate_id' => $faker -> numberBetween($min = 60, $max = 190),
                'quota_aid_procedure_id' => $faker->numberBetween($min = 1, $max = 2),                
                'procedure_modality_id' => $faker -> numberBetween($min = 1, $max = 12),
               'city_start_id' => $faker -> numberBetween($min = 1, $max = 9),
                'city_end_id' => $faker -> numberBetween($min = 1, $max = 9),
                'code' => str_random(8),
                'reception_date' => $faker -> date($format = 'Y-m-d', $max = 'now'),     
                'subtotal' =>$faker-> numberBetween($min = 1000, $max = 10000), 
                'total'=> $faker -> numberBetween($min = 10000, $max = 15000), 
                'workflow_id'=> $faker -> numberBetween($min = 1, $max = 7),
                'wf_state_current_id' => $faker -> numberBetween($min = 3, $max = 32),
                ]);
        }        
    }
}
