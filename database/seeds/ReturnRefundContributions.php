<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReturnRefundContributions extends Seeder
{
    public function run()
    {
        DB::statement("
            SELECT setval(
                'ret_fun_refund_types_id_seq',
                (SELECT COALESCE(MAX(id), 1) FROM ret_fun_refund_types)
            )
        ");

        DB::table('ret_fun_refund_types')->insert([
            'contribution_type_id' => 15,
            'annual_percentage_yield' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}