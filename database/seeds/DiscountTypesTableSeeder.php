<?php

use Illuminate\Database\Seeder;

class DiscountTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('discount_types')->insert([
            ['module_id' => '3', 'name' => 'Anticipo Fondo de Retiro', 'shortened' => 'Anticipo Fondo de Retiro'],
            ['module_id' => '3', 'name' => 'Retención para pago de préstamo', 'shortened' => 'Retención para pago de préstamo'],
            ['module_id' => '3', 'name' => 'Retención para garantes', 'shortened' => 'Retención para garantes'],
        ]);
    }
}
