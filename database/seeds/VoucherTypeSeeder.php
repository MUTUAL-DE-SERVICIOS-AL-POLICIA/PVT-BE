<?php

use Illuminate\Database\Seeder;

class VoucherTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'Aporte Directo Fondo de Retiro / Cuota Mortuoria', 'module_id' => ''],
            ['name' => 'Aporte Directo Auxilio Mortuorio', 'module_id' => ''],
            ['name' => 'Amortización de Préstamos', 'module_id' => ''],
            ['name' => 'Gastos Administrativos', 'module_id' => ''],
            ['name' => 'Venta de Folders Fondo de Retiro', 'module_id' => ''],
            ['name' => 'Venta de Folders Cuota Mortuoria', 'module_id' => ''],
            ['name' => 'Venta de Folders Auxilio Mortuorio', 'module_id' => ''],
            ['name' => 'Ingresos Adicionales', 'module_id' => ''],
            ['name' => 'Otros', 'module_id' => ''],
        ];
        foreach ($types as $type) {
            VoucherType::create($type);
        }        
    }
}
