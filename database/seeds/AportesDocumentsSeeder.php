<?php

use Illuminate\Database\Seeder;

class AportesDocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('procedure_documents')->insert([
          ['name' => 'Fotocopia contrato de declaracion de pensión.'],
          ['name' => 'Fotocopia contrato emitido por la AFP'],
          ['name' => 'Fotocopia de boleta de pago'],
          ['name' => 'Nota dirigida al Director General Ejecutivo de la MUSERPOL, solicitando el registro en el sistema para efectivizar aportes directos'],
          ['name' => 'Resolución de designación'],
          ['name' => 'Memorándum de designación'],
          ['name' => 'Compromiso de pago'],
          ['name' => 'Resolución de suspensión de funciones y haberes'],
          ['name' => 'Memorándum de suspensión de funciones y haberes']
      ]);
    }
}