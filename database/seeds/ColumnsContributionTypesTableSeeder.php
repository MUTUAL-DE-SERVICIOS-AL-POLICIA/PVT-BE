<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Contribution\ContributionType;

class ColumnsContributionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $c = ContributionType::find(10);
        $c->display_name = 'Reconocimiento de aportes en disponibilidad';
        $c->save();
    }
}
