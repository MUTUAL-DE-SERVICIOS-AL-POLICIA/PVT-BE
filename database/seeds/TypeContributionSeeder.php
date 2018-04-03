<?php

use Illuminate\Database\Seeder;

class TypeContributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('contribution_types')->insert([
            ['name' => 'Servicio', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
            ['name' => 'Disponibilidad', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
            ['name' => 'Item 0', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
        ]);
    }
}











