<?php

use Illuminate\Database\Seeder;

class DocumentosAdicionalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('procedure_documents')->insert([
            ['name' => 'Resolución sobre la adhesión del alfanúmero a la Cedula de Identidad del titular emitido por el SEGIP (original).'],
            ['name' => 'Resolución sobre la adhesión del alfanúmero a la Cedula de Identidad del titular emitido por el SEGIP (fotocopia legalizada).'],            
        ]);
    }
}
