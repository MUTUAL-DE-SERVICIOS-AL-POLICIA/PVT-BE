<?php

use Illuminate\Database\Seeder;

class AddAffiliateStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                
        DB::table('affiliate_states')->insert([
            ['affiliate_state_type_id' => '1', 'name' => 'Agregado Policial']
        ]);
    }
}
