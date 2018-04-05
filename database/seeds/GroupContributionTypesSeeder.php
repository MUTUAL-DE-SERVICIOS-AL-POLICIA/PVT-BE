<?php

use Illuminate\Database\Seeder;

class GroupContributionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
         DB::table('group_type_contributions')->insert([
            ['name' => 'Comando'],
            ['name' => 'Aporte Directo'],
            ['name' => 'No Aporte']
        ]);
    }
}
