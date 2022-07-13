<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Contribution\ContributionType;
use Muserpol\Models\Contribution\ContributionTypeQuotaAid;

class AddTypeContributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //fondo de retiro
        $contribution_types = [
            ['name' => 'Disponibilidad Con Aporte','shortened' => '','description'=>'Periodos que el funcionario del sector activo de la Policía Boliviana estuvo en situación de disponibilidad de la letra "A" o la letra "C"','operator'=>'-','sequence'=>'12'],
            ['name' => 'Disponibilidad Sin Aporte','shortened' => '','description'=>'Periodos que el funcionario del sector activo de la Policía Boliviana estuvo en situación de disponibilidad de la letra "A" o la letra "C"','operator'=>'-','sequence'=>'13'],
        ];
        foreach ($contribution_types as $contribution_type) {
            ContributionType::firstOrCreate($contribution_type);
        }

        //cuota y auxilio_mortuorio
        $contribution_type_quota_aids = [
            ['name' => 'Periodo Con Aporte','shortened' => '','description'=>'Periodo Con Aporte','operator'=>'+','sequence'=>'1'],
            ['name' => 'Periodo Sin Aporte','shortened' => '','description'=>'Periodo Sin Aporte','operator'=>'-','sequence'=>'2'],
        ];
        foreach ($contribution_type_quota_aids as $contribution_type_quota_aid) {
            ContributionTypeQuotaAid::firstOrCreate($contribution_type_quota_aid);
        }
    }
}
