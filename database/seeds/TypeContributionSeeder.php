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
            ['name' => 'Servicio', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27','group_type_contribution_id'=>1],
            ['name' => 'Disponibilidad', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27','group_type_contribution_id'=>1],
            ['name' => 'Item 0', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27','group_type_contribution_id'=>2],
            ['name' => 'Batallon de Seguridad Fisica', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27','group_type_contribution_id'=>3],
            ['name' => 'No Hay Regristro', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27','group_type_contribution_id'=>3],
            ['name' => 'Registro Segun CAS', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27','group_type_contribution_id'=>3],
        ]);
    }
}











