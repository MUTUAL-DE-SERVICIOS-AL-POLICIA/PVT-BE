<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Contribution\ContributionType;

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
        // DB::table('contribution_types')->insert([
        //     ['name' => 'Servicio', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
        //     ['name' => 'Disponibilidad', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
        //     ['name' => 'Item 0', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
        //     ['name' => 'Batallon de Seguridad Fisica', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
        //     ['name' => 'No Hay Regristro', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
        //     ['name' => 'Registro Segun CAS', 'shortened' => ' ', 'created_at' => '2018/03/27', 'updated_at' => '2018/03/27'],
        // ]);
        foreach (ContributionType::all() as $value) {
            $value->delete();
        }
        $statuses = [
            ['name' => 'Período reconocido por comando ', 'shortened' => ''],
            ['name' => 'Período en item 0 Con Aporte', 'shortened' => ''],
            ['name' => 'Período en item 0 Sin Aporte', 'shortened' => ''],
            ['name' => 'Período de Batallón de Seguridad Física Con Aporte', 'shortened' => ''],
            ['name' => 'Período de Batallón de Seguridad Física Sin Aporte', 'shortened' => ''],
            ['name' => 'Periodos anteriores a Mayo de 1976 Sin Aporte', 'shortened' => ''],
            ['name' => 'Período Certificación Con Aporte', 'shortened' => ''],
            ['name' => 'Período Certificación Sin Aporte', 'shortened' => ''],
            ['name' => 'Período no Trabajado', 'shortened' => ''],
            ['name' => 'Disponibilidad', 'shortened' => '']
        ];
        foreach ($statuses as $status) {
            ContributionType::create($status);
        }
    }
}
