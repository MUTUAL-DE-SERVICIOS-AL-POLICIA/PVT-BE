<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ObservationType;

class AddObservadoCuentaBancariaObservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ObservationType::updateOrCreate(
            ['id' => 63],
            [
                'module_id' => 2,
                'name' => 'Observado - Cuenta Bancaria',
                'description' => 'Subsanable',
                'type' => 'AT',
                'shortened' => 'Observado Cuenta Bancaria',
                'active' => true
            ]
        );
    }
}
