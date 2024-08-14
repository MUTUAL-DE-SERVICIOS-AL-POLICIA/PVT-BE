<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddDiscountTypeSeeder extends Seeder
{
   public function run()
   {
      DB::table('discount_types')->insert([
         'module_id' => 4,
         'name' => 'Retención mediante Resolución Judicial o Requerimiento Fiscal',
         'shortened' => 'Retención según juzgado',
         'created_at' => Carbon::now(),
         'updated_at' => Carbon::now()
      ]);
   }
}