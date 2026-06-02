<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateEcoComStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // State: EJECUTADO TOTAL AMORTIZADO
        DB::table('eco_com_states')->updateOrInsert(
            ['id' => 18],
            [
                'name' => 'Ejecutado Total Amortizado',
            ]
        );

        // State: HABILITADO TOTAL AMORTIZADO
        DB::table('eco_com_states')->updateOrInsert(
            ['id' => 32],
            [
                'eco_com_state_type_id' => 6, // Enviado
                'name' => 'Habilitado Total Amortizado',
            ]
        );
    }
}
