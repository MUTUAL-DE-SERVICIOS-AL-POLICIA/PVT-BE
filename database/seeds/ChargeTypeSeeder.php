<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ChargeType;

class ChargeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $charges = [
            ['name' => 'Venta de folder', 'module_id' => '2', 'amount' => '20' ],
            ['name' => 'Venta de folder', 'module_id' => '3', 'amount' => '25' ],
            ['name' => 'Venta de folder', 'module_id' => '4', 'amount' => '25' ],
            ['name' => 'Gastos Administrativos', 'module_id' => '6', 'amount' => '20' ],
        ];
        foreach ($charges as $charge) {
            ChargeType::create($charge);
        }
    }
}
