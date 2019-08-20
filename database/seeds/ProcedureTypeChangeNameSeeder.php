<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\ProcedureModality;

class ProcedureTypeChangeNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procedure = ProcedureType::whereName('Pago de Fondo de Retiro')->first();
        $procedure->name = 'Beneficio de Pago de Fondo de Retiro Policial Solidario';
        $procedure->save();

        $procedure = ProcedureModality::whereName('Jubilación debido a reincorporación')->first();
        $procedure->name = 'Jubilación debido a Reincorporación';
        $procedure->save();
    }
}
