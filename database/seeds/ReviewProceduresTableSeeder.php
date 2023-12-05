<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Muserpol\Models\EconomicComplement\ReviewProcedure;

class ReviewProceduresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reviewProcedures = [
            ['name' => 'Revisión y valoración de requisitos.', 'active' => true],
            ['name' => 'Verificación del cumplimiento del semestre de sus prestaciones y como jubilado.', 'active' => true],
            ['name' => 'Verificación de observaciones.', 'active' => true],
            ['name' => 'Verificación del descuento para el aporte de auxilio mortuorio en la calificación (En caso del Sistema Integral de Pensiones).', 'active' => true],
            ['name' => 'Verificación de importes de la pensión o renta de jubilación, invalidez y muerte según corresponda.', 'active' => true],
            ['name' => 'Verificación y registro de la partida matrimonial o unión libre.', 'active' => true],
            ['name' => 'Verificación de formulario SIGEP.', 'active' => true]
        ];

        foreach ($reviewProcedures as $procedureData) {
            $reviewProcedure = new ReviewProcedure($procedureData);
            $reviewProcedure->save();
        }
    }
}
