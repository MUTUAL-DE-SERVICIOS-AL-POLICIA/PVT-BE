<?php

use Illuminate\Database\Seeder;

class ProcedureStateUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            ['name' => 'Pagado', 'description' => 'Trámite ya pagado.'],
            ['name' => 'Cobrado', 'description' => 'Trámite ya cobrado.'],
            ['name' => 'Anulado', 'description' => 'Trámite anulado.'],
            
        ];
        foreach ($states as $state) {
            \Muserpol\Models\ProcedureState::create($state);
        }
    }
}
