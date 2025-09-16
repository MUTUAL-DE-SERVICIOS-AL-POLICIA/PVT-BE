<?php

use Illuminate\Database\Seeder;

class DataBackupAverageQuotable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('retirement_funds')->update([
            'used_limit_average' => DB::raw('average_quotable')
        ]);


        DB::table('ret_fun_procedures')->where('id', 1)->update([
            'limit_average' => 10800
        ]);
    }
}

//php artisan db:seed --class=DataBackupAverageQuotable
//php artisan db:seed --class=CreateDevolutionContributionType13022025