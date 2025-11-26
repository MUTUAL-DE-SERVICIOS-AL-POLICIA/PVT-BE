<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateReferencialLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("
           UPDATE eco_com_rents ecr
            SET referencial_limit = ecp.indicator
            FROM eco_com_procedures ecp
            WHERE ecr.semester = ecp.semester
            AND ecr.year = ecp.year
        ");
    }
}
