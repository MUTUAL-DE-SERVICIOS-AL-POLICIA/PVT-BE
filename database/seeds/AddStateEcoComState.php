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
        // Creando nueva exclusión
        EcoComState::firstOrCreate([
            'eco_com_state_type_id' => 4,
            'name' => 'No Pagado - Exclusión Definitiva'
        ]);

        EcoComState::where('name', 'like', 'No Pagado - Excluido')->update(['name' => 'No Pagado - Exclusión del Semestre']);
    }
}
