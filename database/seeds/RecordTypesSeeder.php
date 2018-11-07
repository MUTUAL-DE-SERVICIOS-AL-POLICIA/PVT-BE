<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\RecordType;

class RecordTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['name' => 'Validación', 'description' => 'Validación de Trámite.'],
            ['name' => 'Cancelado', 'description' => 'Trámite Cancelado.'],
            ['name' => 'Derivación', 'description' => 'Derivación de un Trámite.'],
            ['name' => 'Devolución', 'description' => 'Devolución de un Trámite.'],
            ['name' => 'Datos Personales', 'description' => 'Datos personales.'],
            ['name' => 'Datos Policiales', 'description' => 'Datos policiales.'],
            ['name' => 'Datos de un Trámite', 'description' => 'Datos de un Trámite.'],
            ['name' => 'Vida Policial', 'description' => 'Datos de la vida policial.'],
        ];
        foreach ($statuses as $status) {
            RecordType::create($status);
        }
    }
}
