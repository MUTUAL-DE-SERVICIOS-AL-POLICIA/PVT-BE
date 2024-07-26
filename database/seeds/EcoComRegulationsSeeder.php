<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\EconomicComplement\EcoComRegulation;



class EcoComRegulationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eco_com_regulations = [
            [
                'description' => 'Reglamento 2024', 
                'is_enable' => true, 
                'replica_eco_com_procedure_id' => 25,
                'start_production_date' => now(), 
                'end_production_date' => '2028-12-31'
            ]
        ];
    
        foreach ($eco_com_regulations as $eco_com_regulation) {
            EcoComRegulation::updateOrCreate(
                ['description' => $eco_com_regulation['description']], // Condición para buscar el registro existente
                $eco_com_regulation // Valores para actualizar o crear
            );
        }
    }
}
