<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\EconomicComplement\EcoComState;

class AddStateEcoComState extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('eco_com_states')->insert([
            'eco_com_state_type_id' => 4,
            'name' => 'No Pagado - Retenido'
        ]);
    }
}
