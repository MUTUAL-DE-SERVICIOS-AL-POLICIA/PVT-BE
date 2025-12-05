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
        DB::table('eco_com_fixed_pensions')
            ->where('affiliate_id', 33204)
            ->whereNull('eco_com_rent_id')
            ->update(['eco_com_rent_id' => 1811]);

        DB::table('economic_complements')
            ->where('affiliate_id', 33204)
            ->whereNull('eco_com_rent_id')
            ->update(['eco_com_rent_id' => 1811]);

        DB::table('eco_com_fixed_pensions')
            ->where('affiliate_id', 49822)
            ->whereNull('eco_com_rent_id')
            ->update(['eco_com_rent_id' => 1872]);

        DB::table('economic_complements')
            ->where('affiliate_id', 49822)
            ->whereNull('eco_com_rent_id')
            ->update(['eco_com_rent_id' => 1872]);

        DB::table('economic_complements')
            ->where('affiliate_id', 53331)
            ->where('id', 3838)
            ->update(['eco_com_rent_id' => 34]);

        DB::table('economic_complements')
            ->where('affiliate_id', 19134)
            ->where('id', 4403)
            ->update(['eco_com_rent_id' => 34]);
    }
}
