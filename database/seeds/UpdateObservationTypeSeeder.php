<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ObservationType;

class UpdateObservationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creando nueva exclusión
        ObservationType::firstOrCreate([
            'module_id' => 2,
            'name' => 'Excluido - Percibe una pensión o renta igual o superior al salario del activo.',
            'description' => 'Denegado',
            'type' => 'AT',
            'shortened' => 'Excluido - Percibe una pensión o renta igual o superior al salario del activo.',
            'active' => true
        ]);

        // Typo de observaciones clasificadas como exclusiones semestrales
        $semiannual_exclusion = [9, 25, 47, 48];
        $semiannual_exclusion_observations = ObservationType::whereIn('id', $semiannual_exclusion)->get();
        foreach( $semiannual_exclusion_observations as $exclusion) {
            $exclusion->type = 'AT';
            $exclusion->save();
        }
    }
}
