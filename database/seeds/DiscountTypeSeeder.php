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
        DB::table('discount_types')->insert([
            'module_id' => 2,
            'name' => 'Amortización Retención mediante Resolución Judicial o Requerimientos Fiscal',
            'shortened' => 'Retención según juzgado',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
