<?php

use Illuminate\Database\Seeder;
use Muserpol\Operation;
use Muserpol\Models\ObservationType;

class ObservationTypeAddSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $observation_types = [
            ['module_id'=>2,'name' => 'Suspendido - Cuentas por cobrar - RF.','description'=>'Amortizable','shortened'=>'Cuentas por cobrar RF','type'=>'AT','active'=>true],
        ];
        foreach ($observation_types as $observation_type) {
            ObservationType::firstOrCreate($observation_type);
        }
    }
}
