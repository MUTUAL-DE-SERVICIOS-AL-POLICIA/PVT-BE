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
        $semiannual_exclusions = collect([
            (object) ['id' => 9, 'name' => 'Excluido - Percibe únicamente una Prestación por Riesgo o Invalidez.'],
            (object) ['id' => 25, 'name' => 'Excluido - Percibe una prestación por invalidez del SENASIR y tiene menos de 65 años de edad.'],
            (object) ['id' => 47, 'name' => 'Excluido - No cumple con el semestre de sus prestaciones.'],
            (object) ['id' => 48, 'name' => 'Excluido - No cumple con el semestre como jubilados de la Policía Boliviana.']
        ]);

        $semiannual_exclusion_observations = ObservationType::whereIn('id', $semiannual_exclusions->pluck('id'))->orderBy('id')->get();
        $counter = 0;
        foreach($semiannual_exclusion_observations as $exclusion) {
            if($exclusion->id == $semiannual_exclusions[$counter]->id) {
                $exclusion->type = 'AT';
                $exclusion->name = $semiannual_exclusions[$counter]->name;
                $exclusion->save();
                $counter++;
            }
        }
        $definitive_exclusions = collect([
            (object) ['id' => 8, 'name' => 'Excluido - Titular no cuenta con al menos (16) dieciséis Años de Servicio en Policía Boliviana.'],
            (object) ['id' => 20, 'name' => 'Excluido - Titular fue dado de baja o solicitó baja voluntaria de la Policía Boliviana.'],
            (object) ['id' => 21, 'name' => 'Excluido - Tiene sentencias condenatorias ejecutoriadas por delitos cometidos contra la MUSERPOL o MUSEPOL.'],
            (object) ['id' => 36, 'name' => 'Excluido - Huérfano absoluto con (25) veinticinco años o más.'],
        ]);

        $definitive_exclusion_observations = ObservationType::whereIn('id', $definitive_exclusions->pluck('id'))->orderBy('id')->get();
        $counter = 0;
        foreach($definitive_exclusion_observations as $exclusion) {
            if($exclusion->id == $definitive_exclusions[$counter]->id) {
                $exclusion->name = $definitive_exclusions[$counter]->name;
                $exclusion->save();
                $counter++;
            }
        }
    }
}
