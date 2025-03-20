<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Contribution\ContributionType;

class CreateDevolutionContributionType13022025 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $contribution = ContributionType::create(['name'=>'Devolución','shortened'=>'D','description'=>'Período devuelto al funcionario por falta de regularización','operator'=>'-']);
        
        $this->command->info("Contribution type - Devolución creado");
    }
}
