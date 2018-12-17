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
            //['id' => '1', 'name' => 'Aporte Directo Fondo de Retiro / Cuota Mortuoria', 'module_id' => '3', 'amount' => '0'],
            ['id' => '2', 'name' => 'Aporte Directo Auxilio Mortuorio', 'module_id' => '4', 'amount' => '0'],
            ['id' => '3', 'name' => 'Amortización de Préstamos', 'module_id' => '6', 'amount' => '0'],
            ['id' => '4', 'name' => 'Gastos Administrativos Activo', 'module_id' => '6', 'amount' => '20'],
            ['id' => '5', 'name' => 'Gastos Administrativos Pasivo', 'module_id' => '6', 'amount' => '10'],
            ['id' => '6', 'name' => 'Venta de Folder Fondo de Retiro', 'module_id' => '3', 'amount' => '25'],
            ['id' => '7', 'name' => 'Venta de Folder Cuota Mortuoria', 'module_id' => '4', 'amount' => '25'],
            ['id' => '8', 'name' => 'Venta de Folder Auxilio Mortuorio', 'module_id' => '4', 'amount' => '25'],            
            ['id' => '9', 'name' => 'Venta de Folder Complemento Económico', 'module_id' => '2', 'amount' => '15'],
        ];
        foreach ($types as $type) {
            Muserpol\Models\VoucherType::create($type);
        }        
    }
}
