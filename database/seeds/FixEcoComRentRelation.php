<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixEcoComRentRelation extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1) affiliate_id = 33204 → eco_com_rent_id = 1811
        DB::table('eco_com_fixed_pensions')
            ->where('affiliate_id', 33204)
            ->whereNull('eco_com_rent_id')
            ->update(['eco_com_rent_id' => 1811]);

        DB::table('economic_complements')
            ->where('affiliate_id', 33204)
            ->whereNull('eco_com_rent_id')
            ->update(['eco_com_rent_id' => 1811]);

        // 2) affiliate_id = 49822 → eco_com_rent_id = 1872
        DB::table('eco_com_fixed_pensions')
            ->where('affiliate_id', 49822)
            ->whereNull('eco_com_rent_id')
            ->update(['eco_com_rent_id' => 1872]);

        DB::table('economic_complements')
            ->where('affiliate_id', 49822)
            ->whereNull('eco_com_rent_id')
            ->update(['eco_com_rent_id' => 1872]);
    }
}
