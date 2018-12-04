<?php

use Illuminate\Database\Seeder;

class AportesComisionRequirements extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('procedure_requirements')->insert([
            //Activo Comision
            ['procedure_modality_id' => '20', 'procedure_document_id' => '195', 'number' => '1'],
            ['procedure_modality_id' => '20', 'procedure_document_id' => '196', 'number' => '2'],
            ['procedure_modality_id' => '20', 'procedure_document_id' => '197', 'number' => '2'],
            ['procedure_modality_id' => '20', 'procedure_document_id' => '40', 'number' => '3'],
            ['procedure_modality_id' => '20', 'procedure_document_id' => '198', 'number' => '4']
          ]);
    }
}
