<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Kinship;

class AddKinshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kinships = [
            ['name' => 'Nieto (a)'],
            ['name' => 'Sobrino (a)']
        ];

        foreach($kinships as $kinship) {
            Kinship::firstOrCreate($kinship);
        }
    }
}
