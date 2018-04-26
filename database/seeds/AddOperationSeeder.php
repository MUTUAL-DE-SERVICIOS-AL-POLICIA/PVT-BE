<?php

use Illuminate\Database\Seeder;

class AddOperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('operations')->insert([
            ['module_id' => '3', 'name' => 'ContributionCommitment', 'created_at' => '2018/04/26', 'updated_at' =>'2018/04/26'],
            ['module_id' => '3', 'name' => 'AidCommitment', 'created_at' => '2018/04/26', 'updated_at' =>'2018/04/26'],
        ]);
    }
}
