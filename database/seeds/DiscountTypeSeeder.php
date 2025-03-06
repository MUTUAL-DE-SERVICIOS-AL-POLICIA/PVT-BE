<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =[
            [
                'module_id' => 2,
                'name' => 'Amortización Retención mediante Resolución Judicial o Requerimientos Fiscal',
                'shortened' => 'Retención según juzgado',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'module_id' => 2,
                'name' => 'Descuento por Amortización de Préstamo Estacional',
                'shortened' => 'Descuento por Préstamo Estacional',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
        foreach ($data as $discount) {
            DB::table('discount_types')->updateOrInsert(
                [
                    'module_id' => $discount['module_id'],
                    'name' => $discount['name']
                ],
                [
                    'shortened' => $discount['shortened'],
                    'created_at' => $discount['created_at'],
                    'updated_at' => $discount['updated_at'],
                ]
            );
        }
        
    }
}
