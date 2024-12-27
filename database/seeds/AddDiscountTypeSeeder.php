<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Muserpol\Models\DiscountType;

class AddDiscountTypeSeeder extends Seeder
{
   public function run()
   {
      DiscountType::firstOrCreate([
         'module_id' => 4,
         'name' => 'Retención mediante Resolución Judicial o Requerimiento Fiscal',
         'shortened' => 'Retención según Resolución Judicial',
         'created_at' => Carbon::now(),
         'updated_at' => Carbon::now()
      ]);
   }
}