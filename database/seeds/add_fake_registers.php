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
                
        for($i=1;$i<150;$i++)
        {
            DB::table('retirement_funds')->insert([
               'id' => $i,
                'user_id'=>$faker->numberBetween($min = 1, $max = 20),
                'affiliate_id' => $faker -> numberBetween($min = 1, $max = 190),
                'procedure_modalities_id' => $faker -> numberBetween($min = 1, $max = 12),
                'ret_fun_procedure_id' => 1,
                'city_start_id' => $faker -> numberBetween($min = 1, $max = 9),
                'city_end_id' => $faker -> numberBetween($min = 1, $max = 9),
                'code' => str_random(8),
                'reception_date' => $faker -> date($format = 'Y-m-d', $max = 'now'),     
                'type' => $faker -> randomElement($array = array ('Pago','Anticipo')),
                'subtotal' =>$faker-> numberBetween($min = 1000, $max = 10000), 
                'total'=> $faker -> numberBetween($min = 10000, $max = 15000), 
                ]);
        }
        
    }
}
