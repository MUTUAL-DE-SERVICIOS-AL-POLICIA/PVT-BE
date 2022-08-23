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
        $c = ContributionType::find(12);
        $c->display_name = 'Devolución de aportes en disponibilidad';
        $c->save();
    }
}
